<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : db_error.php
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
    <p>
        <?php echo $data['error_text']; ?>
    </p>
</body>
</html>