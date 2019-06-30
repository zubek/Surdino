<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 * Date: 29.06.19
 * Time: 21:58
 */

?>

<div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">
            <br><br>
            <h1 class="header center white-text">Образование в тишине</h1>
            <div class="row center">
                <h5 class="header col s12 light"></h5>
            </div>
<!--            <div class="row center">-->
<!--                <a href="/player/request" id="download-button" class="btn-large waves-effect waves-light light-blue darken-2">Попросить перевести</a>-->
<!--            </div>-->
            <br><br>

        </div>
    </div>
    <div class="parallax"><img src="/media/surdoperevod.jpg" alt="Фон на главной странице"></div>
</div>
<br>
<div class="container">
    <div class="row center">
        <div class="col s2"></div>
        <div class="col s2">
            <img class="navigate circle hoverable wow" src="/media/test.png">
            Образование
        </div>
        <div class="col s2">
            <img class="navigate circle hoverable wow" src="/media/tools.png">Творчество
        </div>
        <div class="col s2">
            <img class="navigate circle hoverable wow" src="/media/theater.png">Развлечения
        </div>
        <div class="col s2">
            <img class="navigate circle hoverable wow" src="/media/test.png">Литература
        </div>
        <div class="col s2"></div>
    </div>
</div>

    <div class="row">
        <div>
            <h5>Новые переводы в библиотеке</h5>
        </div>

    <?php

        foreach($data as $video): ?>

            <?php
            if($video["videoType"] != 1){continue;}
            ?>

            <div class="col s4">

                <div class="card center">

                        <p><?= $video['videoName']?></p>
                        <a href="/player/video/<?= $video["id"]?>"><img src="https://i.ytimg.com/vi/<?= $video['videoLink']?>/mqdefault.jpg"></a>

                    <div class="card-action">
                        <div class="chip">
                            <img src="/media/author.png" alt="Автор перевода">
                            Иван Иванов
                        </div>
                        <div class="chip">
                            <img src="/media/circlestar.png" alt="Рейтинг">
                            175
                        </div>
                    </div>

                </div>

            </div>
        <?php endforeach;?>
    </div>



    <div class="row">
        <h5>Новые запросы на перевод</h5>
        <?php

        foreach($data as $video): ?>


        <div class="col s4">

            <div class="card center">

                <p><?= $video['videoName']?></p>
                <img src="https://i.ytimg.com/vi/<?= $video['videoLink']?>/mqdefault.jpg">

                <div class="card-action">

                </div>

            </div>

        </div>
        <?php endforeach;?>
    </div>


<script>

    $(document).ready(function(){

        $(".navigate").hover(function(){
            $( this ).addClass("pulse");
        },function(){
            $( this ).removeClass("pulse");
        })

    })

</script>

