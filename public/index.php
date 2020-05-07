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
        echo isset($_SESSION['id'])? htmlspecialchars(select_user_by_id($con, $_SESSION['id'])['pseudo'].' ') : '';
        disconnect_db($con);
        ?>!
    </div>
</div>
<div class="card border-color-0 back-color-0" style=" text-align: center; margin-top:50px">
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
    <div class="card-body back-color-0 ">
        <div id="MyLists" class="container-fluid">
            <?php
            if(isset($_SESSION['id']))
            {
                $con = connect_db();
                $lists = select_lists_by_user_id($con, $_SESSION['id']);
                while($list = mysqli_fetch_array($lists))
                {
                    $todo_content = '<div class="card back-color-1" style="margin-bottom: 50px;">';
                    $todo_content .= '<div class="card-header back-color-3 color-0" style="font-size: x-large">'. htmlspecialchars($list['name']). '</div>';
                    $todo_content .= '<p class="card-text color-4" style="margin-top:20px; margin-bottom: 20px">' . htmlspecialchars($list['description']) . '</p>';
                    $todo_content .= '</div>';
                    echo $todo_content;
                }
                mysqli_free_result($lists);
                disconnect_db($con);
            }
            ?>
        </div>
        <div id="SharedLists" class="container-fluid" style="display: none">
            shared!
        </div>
    </div>
</div>

<script src="script/script_index.js"></script>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>

