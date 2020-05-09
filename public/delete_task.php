<?php
    require('../private/config.php');
    $con = connect_db();
    if(is_post_request()
        && isset($_POST['task_id'])
        && isset($_SESSION['id'])
        && isset($_POST['list_id']))
    {
        if(user_has_task($con, $_POST['task_id'], $_SESSION['id'], $_POST['list_id']) && delete_task_by_id($con, $_POST['task_id']))
        {
            disconnect_db($con);
            http_response_code(200);
        }
        else
        {
            disconnect_db($con);
            http_response_code(404);
        }
    }
    else
    {
        disconnect_db($con);
        http_response_code(500);
    }
