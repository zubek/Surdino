<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */

?>

<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">
            <br><br>
            <h1 class="header center black-text">Образование в тишине</h1>
            <div class="row center">
                <h5 class="header col s12 light"></h5>
            </div>

            <br><br>

        </div>
    </div>
    <div class="parallax"><img src="/media/surdoperevod2.jpg" alt="Фон на главной странице"></div>
</div>

<div class="container">
    <h5>Оставить заявку на сурдоперевод</h5>
    <div class="row">
        <form class="col s12" action="/player/request" method="post">
            <div class="row">
                <div class="input-field col s6">
                    <input placeholder="Ссылка на видео" id="request_link" type="text" class="validate" name="vlink">
                    <label for="requset_link">Ссылка</label>
                </div>
                <div class="input-field col s6">
                    <select name="vcat">
                        <option value="" disabled selected>Укажите категорию</option>
                        <option value="1">Образование</option>
                        <option value="2">Творчество</option>
                        <option value="3">Развлечения</option>
                        <option value="4">Религия</option>
                    </select>
                    <label>Категория</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="changeName" name="vname" type="text" class="validate">
                    <label for="changeName">Изменить название</label>
                </div>
            </div>
            <div class="row center">
                <button type="submit" class="btn-large waves-effect waves-light light-blue darken-2">Отправить</button>
            </div>
            <input type="hidden" name="action" value="request">
        </form>
    </div>

</div>

<script>

    $(document).ready(function(){
        $('select').formSelect();
    });

</script>