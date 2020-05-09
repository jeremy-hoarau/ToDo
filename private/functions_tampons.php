<?php
function display_lists_task_page()
{
    if (isset($_SESSION['id'])) {
        $con = connect_db();
        $lists = select_lists_by_user_id($con, $_SESSION['id']);
        if (mysqli_num_rows($lists)!= 0){
            while ($list = mysqli_fetch_array($lists)) {
                $todo_content = "<option>". htmlspecialchars($list['name']) ."</option>";
                echo $todo_content;
            }
        }
        else{
            echo "<option>------ No Todo List ------</option>";
        }
        mysqli_free_result($lists);
        disconnect_db($con);
    }
}
?>
