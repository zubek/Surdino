<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : default.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : template
 * 
 * Description:
 *
 * @var array $data
 */
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="description" content="ShuCore system">
    <meta name="keywords" content="development">

    <title>Ошибка</title>
</head>
<body>
<?php if($data['debug_info'] == null){?>
<p>
    Произошла ошибка на стороне сервера.
</p>
<?php }else
{
    echo '<p>'.$data['error_text'].'<br />';
    echo 'Ошибка в файле: '.$data['debug_info']['file'].'<br/>';
    echo 'Номер строки: '.$data['debug_info']['line'].'</p>';}?>
</body>
</html>