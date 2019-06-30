<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File : settings.php
 * Author : Anton Tarkhanov
 * Email : imkekx@gmail.com
 * Type : core
 *
 * Description:
 */
class appSettings{
    /**
     * Адрес хоста MySQL базы данных
     * @var string
     */
    public $db_host = 'localhost';

    /**
     * Логин для подключения к базе данных
     * @var string
     */
    public $db_user = 'root';

    /**
     * Пароль для подключения к базе данных
     * @var string
     */
    public $db_password = 'rfrjrbev';

    /**
     * Имя базы данных
     * @var string
     */
    public $db_name = 'surdino';

    /**
     * Переключатель режимов Debug/Production
     * @var bool
     */
    public $debug_mode = true;

    /**
     * Имя параметра идентифицирующего административную часть
     * @var string
     */
    public $admin_tag = 'staff';

    /**
     * Контроллер по умолчанию для административной части
     * @var string
     */
    public $default_admin_controller = 'frontpage';

    /**
     * Если установлено в true будут засекаться временные интервалы
     * выполнения операций в критических точках скрипта.
     * @var bool
     */
    public $use_timers = true;

    /**
     * Переключатель режима обязательной авторизации для всех пользователей
     * @var bool
     */
    public $auth_strict = true;

    /**
     * Ключ для выполнения cron операций
     * @var string
     */
    public $cron_key = 'romper_stomper';

    /**
     * Имя папки с шаблонами ошибок
     *
     * @var string
     */
    public $error_template = 'default';
}