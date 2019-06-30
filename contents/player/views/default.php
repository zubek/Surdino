<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */

$video = json_encode($data);
?>

<script>

    var VIDEO = <?= $video?>;

</script>

<div class="container">
    <div class="s12">
        <h4><?= $data['videoName']?></h4>
    </div>
</div>

<div class="row">
    <div class="col s7">
        <div class="video-container">
            <div id="player1"></div>
        </div>
    </div>
    <div class="col s5">

            <div class="video-container">
                <div id="player2"></div>
            </div>

        <br>
        <a id="player-play" class="waves-effect waves-light  btn-large"><i class="material-icons center">play_arrow</i></a>
        <a id="player-pause" class="waves-effect waves-light light-blue darken-2 btn-large"><i class="material-icons center">pause</i></a>
    </div>
</div>