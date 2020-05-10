<?php
    require('../private/config.php');
    if(is_post_request() && isset($_POST['task_id']) && isset($_POST['list_id']) && isset($_SESSION['id']))
    {
        $con = connect_db();
        if (user_has_task($con, $_POST['task_id'], $_SESSION['id'], $_POST['list_id']))
        {
            $result = select_task_by_id($con, $_POST['task_id']);
            $task = mysqli_fetch_assoc($result);
            $state = $task['state'];
            mysqli_free_result($result);

            $state = 1 - $state;
            if(update_task_state($con, $_POST['task_id'], $state) >= 1)
            {
                disconnect_db($con);
                http_response_code(200);
            }
            else
            {
                disconnect_db($con);
                http_response_code(500);
            }
        }
        else
        {
            disconnect_db($con);
            http_response_code(500);
        }
    }
    else
        http_response_code(500);