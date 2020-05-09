<?php
require('../private/config.php');
if(!isset($_SESSION['id']))
    redirect_to("log.php");
$page_name = 'Edit task';

$task_name = '';
$task_state = '';
$task_description = '';

$task_id = $_GET['task_id'] ? $_GET['task_id'] : '';
$task_todo = $_GET['list_id'] ? $_GET['list_id'] : '';

ob_start();
if(is_get_request()){
    if ($task_id != '' && $task_todo != ''){
        $connexion = connect_db();
        $result = select_task_by_id($connexion, $task_id);
        $task = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        disconnect_db($connexion);
    }
}
if (is_post_request()){
    $task_name = $_POST['name'] ? $_POST['name'] : '';
    $task_state = $_POST['state'] ? $_POST['state'] : '';
    $task_description = $_POST["description"] ? $_POST["description"] : '';

    if ($task_name != '' && $task_state != '' && $task_description != ""){
        if ($task_state == "In progress"){
            $task_state = 0;
        }
        elseif ($task_state == "Done"){
            $task_state = 1;
        }
        $connexion = connect_db();
        $result = update_task($connexion, $task_id, $task_name, $task_state, $task_description);
        mysqli_free_result($result);
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
                            <h3 class="text-center ">Edit a task</h3>
                            <div class="form-group">
                                <label for="name">Task name:</label><br>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $task['name'];?>">
                            </div>
                            <div class="form-group">
                                <label for="state">Select a state for the task:</label>
                                <select class="form-control" name="state" id="state">
                                    <?php
                                        if ($task['state'] == 0){
                                            echo "<option>In progress</option>
                                                  <option>Done</option>";
                                        }
                                        elseif ($task['state'] == 1){
                                            echo "<option>Done</option>
                                                  <option>In progress</option>";
                                        }
                                    ?>
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" style="text-align: left"><?php echo $task['description'];?></textarea>
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