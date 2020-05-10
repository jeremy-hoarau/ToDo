<?php
require('../private/config.php');
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

    if ($task_todo != '' && $task_name != '' && $task_state != ''){
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
    if ($task_name == ''){
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                            The name cannot be empty!
                            </div>";
    }
}

?>

    <div id="login" style="margin-top:60px">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center color-4">Create a task</h3>
                            <div class="form-group">
                                <label for="name" class="color-4">Task name:</label><br>
                                <input type="text" name="name" id="name" class="form-control back-color-0 border-color-0">
                            </div>
                            <div class="form-group">
                                <label for="state" class="color-4">Select a state for the task:</label>
                                <select class="form-control back-color-0 border-color-0" name="state" id="state">
                                    <?php
                                        if(is_post_request()){
                                            if($task_state == 'In progress'){
                                                echo "<option>In progress</option>
                                                      <option>Done</option>";
                                            }
                                            elseif ($task_state == 'Done'){
                                                echo "<option>Done</option>
                                                      <option>In progress</option>";
                                            }
                                        }
                                        else{
                                            echo "<option>In progress</option>
                                                      <option>Done</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description" class="color-4">Description</label>
                                <textarea class="form-control back-color-0 border-color-0" name="description" id="description" rows="4"><?php echo (is_post_request() && isset($_POST['description']))? $_POST['description']: '';?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md back-color-4 border-color-4 color-0" value="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php'); ?>