<?php
    require('../private/config.php');
    ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-8" id="list-container" style="background-color: lightblue; text-align: center; width:70vw">
            TODO Lists
        </div>
        <div class="col" id="notif-container" style="background-color: beige; text-align: center">
            Notifications
        </div>
    </div>
</div>

<button onclick="Notif()">
    Test Notif
</button>

<script>
    function Notif(){
        $("#Notif").toggle();
    }

</script>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>

