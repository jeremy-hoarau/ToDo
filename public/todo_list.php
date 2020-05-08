<?php
    require('../private/config.php');
    if(!isset($_SESSION['id']))
        redirect_to("log.php");
    ob_start();

    if(is_get_request() && isset($_GET['id']))
    {
        $con = connect_db();
        // todo_list
        $list = select_list_by_id($con, $_GET['id']);
        $page_name = $list['name'];
        ?>
        <div class="text-center">
            <div class="badge badge-pill badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
                Todo List : <?php echo htmlspecialchars($list['name']);?>
            </div>
        </div>
        <?php
        // tasks
        $tasks = select_tasks_by_list_by_id($con, $_GET['id']);
        while($task = mysqli_fetch_array($tasks))
        {
            echo $task['name'] . '<br>';
            echo $task['description'] . '<br>';
            echo $task['state'] . '<br>';
            echo '<br><br>';
        }
        mysqli_free_result($tasks);
        disconnect_db($con);
    }
    else
    {
        redirect_to('index.php');
    }
?>


<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php'); ?>