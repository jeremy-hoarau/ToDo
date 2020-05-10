<?php
    require('../private/config.php');
    if (is_get_request() && isset($_GET['list_id'])) {
        $con = connect_db();
        $result = select_in_progress_tasks_by_list_id($con, $_GET['list_id']);
        $tasks_ids = array();
        while ($task = mysqli_fetch_assoc($result)) {
            array_push($tasks_ids, (int)$task['id']);
        }
        mysqli_free_result($result);
        disconnect_db($con);
        echo json_encode($tasks_ids);
    }