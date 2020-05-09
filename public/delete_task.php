<?php
    require('../private/config.php');
    $con = connect_db();
    if(is_post_request()
        && isset($_POST['ids'][0])
        && isset($_SESSION['id'])
        && isset($_POST['list_id']))
    {
        foreach ($_POST['ids'] as $task_id)
        {
            if(user_has_task($con, $_POST['id'], $_SESSION['id'], $_POST['id']))
                delete_task_by_id($con, $_POST['id']);
        }
        disconnect_db($con);
        http_response_code(200);
    }
    else
    {
        disconnect_db($con);
        http_response_code(500);
    }
