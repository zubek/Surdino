<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : ipgeobase.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : library
 * 
 * Description:
 */

class libIpgeobase{

    private $fhandleCIDR, $fhandleCities, $fSizeCIDR, $fsizeCities;

    /*
     * @brief Конструктор
     *
     * @param CIDRFile файл базы диапазонов IP (cidr_optim.txt)
     * @param CitiesFile файл базы городов (cities.txt)
     */
    function __construct()
    {

        $this->fhandleCIDR = fopen(ROOT_PATH.'/libs/ipgeobase/cidr_optim.txt', 'r');
        $this->fhandleCities = fopen(ROOT_PATH.'/libs/ipgeobase/cities.txt', 'r');
        $this->fSizeCIDR = filesize(ROOT_PATH.'/libs/ipgeobase/cidr_optim.txt');
        $this->fsizeCities = filesize(ROOT_PATH.'/libs/ipgeobase/cities.txt');
    }

    /*
     * @brief Получение информации о городе по индексу
     * @param idx индекс города
     * @return массив или false, если не найдено
     */
    private function getCityByIdx($idx)
    {
        rewind($this->fhandleCities);
        while(!feof($this->fhandleCities))
        {
            $str = fgets($this->fhandleCities);
            $arRecord = explode("\t", trim($str));
            if($arRecord[0] == $idx)
            {
                return array(	'city' => $arRecord[1],
                    'region' => $arRecord[2],
                    'district' => $arRecord[3],
                    'lat' => $arRecord[4],
                    'lng' => $arRecord[5]);
            }
        }
        return false;
    }

    /*
     * @brief Получение гео-информации по IP
     * @param ip IPv4-адрес
     * @return массив или false, если не найдено
     */
    function getRecord($ip)
    {
        $ip = sprintf('%u', ip2long($ip));

        rewind($this->fhandleCIDR);
        $rad = floor($this->fSizeCIDR / 2);
        $pos = $rad;
        while(fseek($this->fhandleCIDR, $pos, SEEK_SET) != -1)
        {
            if($rad)
            {
                $str = fgets($this->fhandleCIDR);
            }
            else
            {
                rewind($this->fhandleCIDR);
            }

            $str = fgets($this->fhandleCIDR);

            if(!$str)
            {
                return false;
            }

            $arRecord = explode("\t", trim($str));

            $rad = floor($rad / 2);
            if(!$rad && ($ip < $arRecord[0] || $ip > $arRecord[1]))
            {
                return false;
            }

            if($ip < $arRecord[0])
            {
                $pos -= $rad;
            }
            elseif($ip > $arRecord[1])
            {
                $pos += $rad;
            }
            else
            {
                $result = array('range' => $arRecord[2], 'cc' => $arRecord[3]);

                if($arRecord[4] != '-' && $cityResult = $this->getCityByIdx($arRecord[4]))
                {
                    $result += $cityResult;
                }

                return $result;
            }
        }
        return false;
    }
}