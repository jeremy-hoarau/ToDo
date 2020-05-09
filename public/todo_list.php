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
        if(user_has_list_by_ids($con, $_SESSION['id'], $_GET['id']))
            $list_access = 2;
        else
            $list_access = get_user_list_access($con, $_SESSION['id'], $_GET['id']);
        //0 -> no access
        //1 -> read access
        //2 -> all access
        if($list_access == 0)
            redirect_to('index.php');
        ?>
        <div class="text-center">
            <div class="badge badge-pill badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
                Todo List : <?php echo htmlspecialchars($list['name']);?>
            </div>
        </div>
        <div style="margin-top:50px; margin-left: 20px;">
            <a class="btn color-0 back-color-4 border-color-4" href="<?php echo url_for('/create_task.php?id='.$_GET['id']); ?>" style="width: 150px; height: 50px;"><p style="margin-top:5px">+ New Task</p></a>
        </div>
        <div class="container-fluid back-color-0"  style="margin-top: 20px; text-align: center; min-height:500px;">
            <div class="row">
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            In Progress:
                        </div>
                    </div>
                </div>
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            Done:
                        </div>
                    </div>
                    <?php if($list_access != 1){ $button_content = "
                    <div style='position: absolute; right: 25px'>
                        <button type='button' class='btn btn-danger' onclick='DeleteAllDoneTasks(\"";
                        $button_content .= $_GET['id'] . "\")'>Delete All Done</button>
                    </div>
                    ";echo $button_content;} ?>
                </div>
            </div>
            <div class="row">
                <?php display_tasks($con, $_GET['id'], $list_access);?>
            </div>
        </div>
        <?php
        disconnect_db($con);
    }
    else
    {
        redirect_to('index.php');
    }
?>

<script>
    function DeleteAllDoneTasks(list_id)
    {
        $.ajax({
            type: "GET",
            url: "get_done_tasks.php?list_id="+list_id,
            contentType: "application/json",
            dataType: "json",
            success: function(response) {
                response.forEach(function(item, index){
                    DeleteTask(item, list_id);
                })
            }
        });
    }

    function DeleteList(id)
    {
        $.post("delete_todo.php", { id: id},
            function(data, status) {
                if(status === 'success')
                    $('#List-' + id).css("display", "none");
            });
    }

    function DeleteTask(task_id, list_id) {
        $.post("delete_task.php", { task_id: task_id, list_id: list_id},
            function(data, status) {
                if(status === 'success')
                    $('.task-' + task_id).remove();
                else
                    alert("error when deleting task");
            });
    }
</script>

<!-- ######################################################################################################

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');

function display_tasks($con, $list_id, $list_access)
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
                "<div id=".$task['id']." class='row task".$i." task-".$task['id'];$task_content.="' style='margin:25px;";
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
                                        <button type='button' class='btn btn-danger' onclick='DeleteTask(";
                $task_content .= $task['id'].",".$list_id;
                $task_content .= ")'>Delete</button>
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
}?>