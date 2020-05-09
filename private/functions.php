<?php
    function url_for($script_path) {
        if($script_path[0] != '/') {
            $script_path = "/" . $script_path;
        }
        return WWW_ROOT . $script_path;
    }

    function redirect_to($location) {
        header("Location: " . $location);
        exit;
    }

    function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    function is_get_request() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }


    //index
    function display_lists($list_request)
    {
        if (isset($_SESSION['id'])) {
            $con = connect_db();
            if($list_request === 'all')
                $lists = select_lists_by_user_id($con, $_SESSION['id']);
            else
                $lists = select_shared_lists_by_user_id($con, $_SESSION['id']);
            while ($list = mysqli_fetch_array($lists)) {
                $todo_content = '<div id="List-'. $list['id'].'" class="card back-color-1" style="margin-bottom: 50px;">
                                        <div class="card-header back-color-3 color-0" style="font-size: x-large">
                                            ' . htmlspecialchars($list['name']) . '
                                        </div>
                                        <div class="container-fluid" style="height: 140px; margin-bottom: 10px">
                                            <div class="row align-items-center">
                                                <div class="col color-4" style="text-align: justify; height: 120px; margin:20px; overflow: auto">
                                                    ' . htmlspecialchars($list['description']) . '
                                                </div>
                                                <div class="col-2">
                                                    <div class="row justify-content-md-center" style="margin:25px">
                                                        <a href="'. url_for('/todo_list.php?id=') . $list['id'] .'" class="btn btn-info">Manage</a>
                                                    </div>
                                                    '; if($list_request === 'all'){$todo_content .=
                                                    '<div class="row justify-content-md-center" style="margin:25px">
                                                        <button type="button" class="btn btn-danger" onclick="DeleteList('. $list['id'] .')">Delete</button>
                                                    </div>';} $todo_content .=
                                                '</div>
                                            </div>
                                        </div>
                                    </div>';
                echo $todo_content;
            }
            mysqli_free_result($lists);
            disconnect_db($con);
        }
    }

function display_tasks($con, $tasks, $list_access)
{
    $task_content = "";
    for($i = 0; $i<2; $i++)
    {
        $task_content.= "<div class='col border-color-4' style='"
        ;if($i == 0)
            $task_content .= "border-right:1px solid;"."'>";
        else
            $task_content .= "border-left:1px solid;"."'>";
        $tasks = select_tasks_by_list_by_id($con, $_GET['id']);
            while($task = mysqli_fetch_assoc($tasks)) {
                $task_content.=
                "<div class='row task-".$task['id'];$task_content.="' style='margin:25px;";
                    if($task['state'] == (1-$i)){$task_content.="display:none";}
                    $task_content.="'>
                    <div class='card border-color-0 back-color-0' style='min-width:100%;'>
                        <div class='card-header back-color-3 color-0' style='text-align: center; font-size: x-large'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    "; if($list_access == 2){$task_content .=
                                    "<div class='col-1'>
                                        <div class='custom-control custom-checkbox'>
                                            <input type='checkbox' class='custom-control-input' id='Task-".$i.$task['id']."'>
                                            <label class='custom-control-label' for='Task-".$i.$task['id']."'></label>
                                        </div>
                                    </div>";
                                    } $task_content .=
                                    "<div class='col'>
                                        ".htmlspecialchars($task['name'])."
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='container back-color-1 color-4'>
                            <div class='row align-items-center'>
                                <div class='col' style='height: 120px; margin:20px; overflow: auto; text-align: justify'>
                                    ".htmlspecialchars($task['description'])."
                                </div>
                                "; if($list_access == 2){$task_content .=
                                "<div class='col-2'>
                                    <div class='row justify-content-md-center' style='margin:25px'>
                                        <a href='". url_for('/edit_task.php?task_id=') . $task['id'] ."&list_id=" .$_GET['id']. "' class='btn btn-info'>Edit</a>
                                    </div>
                                    <div class='row justify-content-md-center' style='margin:25px'>
                                        <button type='button' class='btn btn-danger' onclick='DeleteTask(".$task['id'].")'>Delete</button>
                                    </div>
                                </div>";
                                    } $task_content .=
                            "</div>
                        </div>
                    </div>
                </div>";
            }
            $task_content .="</div>";
            mysqli_free_result($tasks);
    }
    echo $task_content;
}
?>

<script>
    function DeleteList(id)
    {
        $.post( "delete_todo.php", { id: id},
            function(data, status) {
                if(status === 'success')
                    $('#List-' + id).css("display", "none");
        });
    }

    function DeleteTask(id) {

    }
</script>