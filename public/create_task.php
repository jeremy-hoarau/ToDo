<?php
require('../private/config.php');
require('../private/functions_tampons.php');
if(!isset($_SESSION['id']))
    redirect_to("log.php");
$page_name = 'Create task';

$task_todo = "";
$task_name = "";
$task_state = "";
$task_description = "";

ob_start();

if (is_post_request()){
    $task_todo = $_GET['id'] ? $_GET['id'] : '';
    $task_name = $_POST['name'] ? $_POST['name'] : '';
    $task_state = $_POST['state'] ? $_POST['state'] : '';
    $task_description = $_POST["description"] ? $_POST["description"] : '';

    if ($task_todo != '' && $task_name != '' && $task_state != '' && $task_description != ""){
        if ($task_state == "In progress"){
            $task_state = 0;
        }
        elseif ($task_state == "Done"){
            $task_state = 1;
        }
        $connexion = connect_db();
        $result = create_new_task($connexion, $task_todo, $task_name, $task_state, $task_description);
        disconnect_db($connexion);
        redirect_to("todo_list.php?id=".$task_todo);
    }

}

?>

    <div id="login" style="margin-top:60px">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center ">Create a task</h3>
                            <div class="form-group">
                                <label for="name">Task name:</label><br>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="state">Select a state for the task:</label>
                                <select class="form-control" name="state" id="state">
                                    <option>In progress</option>
                                    <option>Done</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php'); ?>