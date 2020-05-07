<?php
    require('../private/config.php');
    if(!isset($_SESSION['id']))
        redirect_to("log.php");
    ob_start();
    $page_name = 'Index';

?>

<div class="text-center">
    <div class="badge badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
        Welcome
        <?php $con = connect_db();
        echo isset($_SESSION['id'])? select_user_by_id($con, $_SESSION['id'])['pseudo'].' ' : '';
        disconnect_db($con);
        ?>!
    </div>
</div>
<div class="container-fluid">
    Your Lists
</div>
<div class="container-fluid">
    <?php
        if(isset($_SESSION['id']))
        {
            $con = connect_db();
            $todo_lists = select_lists_by_user_id($con, $_SESSION['id']);
            foreach($todo_lists as $todo_list)
            {
                $list = mysqli_fetch_assoc($todo_list);
                echo $list['name'] . '<br>';
            }
        }
    ?>
</div>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>

