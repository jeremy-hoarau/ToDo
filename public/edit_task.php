<?php
require('../private/config.php');
if(!isset($_SESSION['id']))
    redirect_to("log.php");


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
        $page_name = 'Edit task - ' . htmlspecialchars($task['name']);
    }
}
if (is_post_request()){
    $task_name = $_POST['name'] ? $_POST['name'] : '';
    $task_state = $_POST['state'] ? $_POST['state'] : '';
    $task_description = $_POST["description"] ? $_POST["description"] : '';

    if ($task_name != '' && $task_state != ''){
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
    if ($task_name == ''){
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                            The name cannot be empty!
                            </div>";
    }
}

?>

    <!-- Previous Page Button -->
    <a href="<?php echo url_for('/todo_list.php?id=' . $_GET['list_id']) ?>">
        <button class="btn back-color-0 border-color-0 color-4" style="position: absolute; margin: 20px">
            <svg class="bi bi-arrow-left" width="2em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 010 .708L3.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                <path fill-rule="evenodd" d="M2.5 8a.5.5 0 01.5-.5h10.5a.5.5 0 010 1H3a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
            </svg>
        </button>
    </a>

    <div id="login" style="margin-top:60px">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center color-4">Edit a task</h3>
                            <div class="form-group">
                                <label for="name" class="color-4">Task name:</label><br>
                                <input type="text" name="name" id="name" class="form-control back-color-0 border-color-0"" value="<?php echo htmlspecialchars($task['name']);?>">
                            </div>
                            <div class="form-group">
                                <label for="state" class="color-4">Select a state for the task:</label>
                                <select class="form-control back-color-0 border-color-0"" name="state" id="state">
                                    <?php
                                        if(is_post_request()){
                                            if($task['state'] == 0 || isset($_POST['state']) && $_POST['state'] == 'In progress'){
                                                echo "<option>In progress</option>
                                                  <option>Done</option>";
                                            }
                                            elseif ($task['state'] == 1 || isset($_POST['state']) && $_POST['state'] == 'Done'){
                                                echo "<option>Done</option>
                                                  <option>In progress</option>";
                                            }
                                        }
                                        else{
                                            if ($task['state'] == 0){
                                                echo "<option>In progress</option>
                                                  <option>Done</option>";
                                            }
                                            elseif ($task['state'] == 1){
                                                echo "<option>Done</option>
                                                  <option>In progress</option>";
                                            }
                                        }
                                    ?>
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description" class="color-4">Description</label>
                                <textarea class="form-control back-color-0 border-color-0" name="description" id="description" rows="4" style="text-align: left"><?php echo is_post_request()? $_POST['description'] : htmlspecialchars($task['description']);?></textarea>
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