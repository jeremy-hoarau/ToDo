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

        <!-- Previous Page Button -->
        <a href="<?php echo url_for('/index.php') ?>">
            <button class="btn back-color-0 border-color-0 color-4" style="position: absolute; margin: 20px">
                <svg class="bi bi-arrow-left" width="2em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 010 .708L3.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M2.5 8a.5.5 0 01.5-.5h10.5a.5.5 0 010 1H3a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                </svg>
            </button>
        </a>

        <div class="text-center">
            <div class="badge badge-pill badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
                Todo List : <?php echo htmlspecialchars($list['name']);?>
            </div>
        </div>
        <div style="margin-top:50px; margin-left: 20px;">
            <?php if($list_access == 2){ echo '
                <a class="btn color-0 back-color-4 border-color-4" href="' . url_for('create_task.php?id='.$_GET['id']) . '" style="width: 150px; height: 50px;"><p style="margin-top:5px">+ New Task</p></a>
                <a class="btn color-0 back-color-4 border-color-4" href="' . url_for('list_access?list_id='.$_GET['id']) . '" style="width: 150px; height: 50px; position:absolute; right: 20px;"><p style="margin-top:5px">Manage Access</p></a>
            ';}?>
        </div>
        <div class="container-fluid back-color-0"  style="margin-top: 20px; text-align: center; min-height:600px;">
            <div class="row">
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            In Progress:
                        </div>
                    </div>
                    <div style='position: absolute; left: 25px'>
                        <button id="ButtonCol-0" type='button' class='btn back-color-0 border-color-4 color-4' onclick='ToggleInProgress()'>
                            hide tasks
                        </button>
                    </div>
                    <?php if($list_access != 1){ $button_content = "
                    <div style='position: absolute; right: 25px'>
                        <button type='button' class='btn btn-success' style='color:black' onclick='CheckOffAllTasks(\"";
                        $button_content .= $_GET['id'] . "\")'>Check off All Tasks</button>
                    </div>
                    ";echo $button_content;} ?>
                </div>
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            Done:
                        </div>
                    </div>
                    <div style='position: absolute; left: 25px'>
                        <button id="ButtonCol-1" type='button' class='btn back-color-0 border-color-4 color-4' onclick='ToggleDone()'>
                            hide tasks
                        </button>
                    </div>
                    <?php if($list_access != 1){ $button_content = "
                    <div style='position: absolute; right: 25px'>
                        <button type='button' class='btn btn-danger' style='color:black' onclick='DeleteAllDoneTasks(\"";
                        $button_content .= $_GET['id'] . "\")'>Delete All \"Done\" Tasks</button>
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

<script src='script/script_todo.js'></script>

<!-- ######################################################################################################

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');

function display_tasks($con, $list_id, $list_access)
{
    $task_content = "";
    for($i = 0; $i<2; $i++)
    {
        $task_content.= "<div class='col border-color-4'";
        $task_content .= "border-right:1px solid;"."'><div id='Col-" . $i . "'>";
        $tasks = select_tasks_by_list_by_id($con, $_GET['id']);
        while($task = mysqli_fetch_assoc($tasks)) {
            $task_content.=
                "<div class='row task".$i." task-".$task['id'];$task_content.="' style='margin:25px;";
            if($task['state'] == (1-$i)){$task_content.="display:none";}
            $task_content.="'>
                    <div class='card border-color-0 back-color-0' style='min-width:100%;'>
                        <div class='card-header back-color-3 color-0' style='text-align: center; font-size: x-large'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    "; if($i == 1 && $list_access == 2) {
                                        $task_content .=
                                            "<button style='color:black' class='btn btn-warning' onclick='ChangeTaskState(";$task_content .=
                                                $task['id'] . ", " . $_GET['id'] . ")'>
                                                <svg class='bi bi-arrow-left' width='1.5em' height=1.5em' viewBox='0 0 16 16' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
                                                  <path fill-rule='evenodd' d='M5.854 4.646a.5.5 0 010 .708L3.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z' clip-rule='evenodd'/>
                                                  <path fill-rule='evenodd' d='M2.5 8a.5.5 0 01.5-.5h10.5a.5.5 0 010 1H3a.5.5 0 01-.5-.5z' clip-rule='evenodd'/>
                                                </svg>Move to: \"In Progress\"
                                            </button>";
                                    }
                                    $task_content .= "<div class='col'>
                                        ".htmlspecialchars($task['name'])."
                                    </div>
                                    "; if($i == 0 && $list_access == 2) {
                                        $task_content .=
                                            "<button style='color:black' class='btn btn-success' onclick='ChangeTaskState(";$task_content .=
                                                $task['id'] . ", " . $_GET['id'] . ")'>Move to: \"Done\" 
                                                <svg class='bi bi-arrow-right' width='1.5em' height='1.5em' viewBox='0 0 16 16' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
                                                  <path fill-rule='evenodd' d='M10.146 4.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L12.793 8l-2.647-2.646a.5.5 0 010-.708z' clip-rule='evenodd'/>
                                                  <path fill-rule='evenodd' d='M2 8a.5.5 0 01.5-.5H13a.5.5 0 010 1H2.5A.5.5 0 012 8z' clip-rule='evenodd'/>
                                                </svg>
                                            </button>";
                                    }
                                $task_content .=
                                "</div>
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
                                        <a href='". url_for('/edit_task.php?task_id=') . $task['id'] ."&list_id=" .$_GET['id']. "' class='btn btn-info' style='color:black'>Edit</a>
                                    </div>
                                    <div class='row justify-content-md-center' style='margin:25px'>
                                        <a type='button' class='btn btn-danger' style='color:black' onclick='DeleteTask(";
                $task_content .= $task['id'].",".$list_id;
                $task_content .= ")'>Delete</a>
                                    </div>
                                </div>";
            } $task_content .=
                "</div>
                        </div>
                    </div>
                </div>";
        }
        $task_content .="</div></div>";
        mysqli_free_result($tasks);
    }
    echo $task_content;
}?>