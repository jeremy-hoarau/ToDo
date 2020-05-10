<?php
    require('../private/config.php');
    if(is_post_request() && isset($_POST['todo_id']) && isset($_SESSION['id']))
    {
        $con = connect_db();
        if(user_accept_todo($con, $_POST['todo_id'], $_SESSION['id']))
        {
            http_response_code(200);
            disconnect_db($con);
        }
        else
        {
            disconnect_db($con);
            http_response_code(500);
        }
    }
    else
        http_response_code(500);