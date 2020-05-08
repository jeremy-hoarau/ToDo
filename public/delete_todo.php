<?php
    require('../private/config.php');
    $con = connect_db();
    if(is_post_request()
        && isset($_POST['id'])
        && isset($_SESSION['id'])
        && user_has_list_by_ids($con, $_SESSION['id'], $_POST['id'])
        && delete_list_by_id($con, $_POST['id']) >= 1)
    {
        disconnect_db($con);
        http_response_code(200);
    }
    else
    {
        disconnect_db($con);
        http_response_code(500);
    }
