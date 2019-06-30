<?php defined('SHU_CORED') or die('Access denied.');
/** @var $doc shuDocument */
/**
 * Created by PhpStorm.
 * User: tyva
 */

$doc->addStyle('materialize.min.css');
$doc->addStyle('surdino.css');
$doc->addScript('materialize.min.js');
$doc->addScript('surdino.js');
$doc->addScript('wow.min.js');
$doc->addStyle('animate.min.css');

?>

<!DOCTYPE>
    <html>

    <head>
           <?php $doc->styles() ?>
    <title> <?php $doc->title() ?> </title>


    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous">
    </script>
    <script src="https://www.youtube.com/iframe_api">

    </script>

    </head>

<body>

<nav class="light-blue darken-2 white-text">
    <div class="nav-wrapper container">
        <a href="/" class="brand-logo">Сурдино</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="#">О проекте</a></li>
            <li><a href="#">Библиотека</a></li>
            <li><a href="#">Переводчику</a></li>
        </ul>
    </div>
</nav>

        <?php $doc->content() ?>
<footer class="page-footer light-blue darken-2">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">СурдинО</h5>
                <p class="grey-text text-lighten-4">Сервис пользовательских сурдопереводов</p>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Ссылки</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">О проекте</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Переводчики</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Контакты</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Помощь проекту</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright light-blue darken-3">
        <div class="container">
            © 2019 Цифровая Тува
            <a class="grey-text text-lighten-4 right" href="#!">#цифровойпрорыв</a>
        </div>
    </div>
</footer>

    <?php $doc->scripts() ?>
<script>
    new WOW().init();
</script>

</body>
</html>