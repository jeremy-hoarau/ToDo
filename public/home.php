<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__. '/config.php');
ob_start();?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8" id="list-container" style="background-color: lightblue; text-align: center">
            TODO Lists
        </div>
        <div class="col" id="notif-container" style="background-color: beige; text-align: center">
            Notifications
        </div>
    </div>
</div>


<?php $content = ob_get_clean();
require(__ROOT__ . '/layout.php');?>

