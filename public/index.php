<?php
    require('../private/config.php');
    if(!isset($_SESSION['id']))
        redirect_to("log.php");
    ob_start();
    $page_name = 'Index';
?>

<div class="text-center">
    <div class="badge badge-pill badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
        Welcome
        <?php $con = connect_db();
        echo htmlspecialchars(select_user_by_id($con, $_SESSION['id'])['pseudo'].' ');
        disconnect_db($con);
        ?>!
    </div>
</div>
<div style="margin-top:50px; margin-left: 20px;">
    <a class="btn color-0 back-color-4 border-color-4" href="<?php echo url_for('/create_todo.php'); ?>" style="width: 150px; height: 50px;"><p style="margin-top:5px">+ New List</p></a>
</div>
<div class="card border-color-0 back-color-0" style=" text-align: center; margin-top:20px">
    <div class="card-header back-color-1">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item ">
                <a class="tab-button nav-link back-color-0 border-color-0 color-4" href="#" onclick="changeTab('my lists')">My Lists</a>
            </li>
            <li class="nav-item">
                <a class="tab-button nav-link border-color-4 color-4 back-color-1" href="#" onclick="changeTab('shared lists');">Shared Lists</a>
            </li>
        </ul>
    </div>
    <div class="card-body back-color-0" style="min-height: 500px;">
        <div id="MyLists" class="container-fluid">
            <?php display_lists('all'); ?>
        </div>
        <div id="SharedLists" class="container-fluid" style="display: none; min-height: 500px;">
            <?php display_lists('shared'); ?>
        </div>
    </div>
</div>



<script src="script/script_index.js"></script>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>

