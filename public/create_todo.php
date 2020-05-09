<?php
require('../private/config.php');
if(!isset($_SESSION['id']))
    redirect_to("log.php");
ob_start();
$page_name = 'New List';

if(is_post_request())
{
    if($_POST['name'] == null or $_POST['name'] == '')
    {
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                                The name cannot be empty!
                                </div>";
    }
    else
    {
        $con =connect_db();
        $added = add_new_list($con, $_POST['name'], $_POST['description'], $_SESSION['id']);
        disconnect_db($con);
        redirect_to('index.php');
    }
}
?>

<div style="margin-top:60px">
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="" method="post">
                        <h3 class="text-center color-4">New List</h3>
                        <div class="form-group">
                            <label for="name" class="color-4">Name:</label><br>
                            <input type="text" name="name" id="name" class="form-control back-color-0 border-color-0"  value="<?php echo (isset($_POST['name']))? $_POST['name'] : "";?>">
                        </div>
                        <div class="form-group">
                            <label for="description" class="color-4">Description:</label><br>
                            <textarea name="description" id="description" class="form-control back-color-0 border-color-0" style="min-height: 150px"><?php echo (isset($_POST['description']))? $_POST['description'] : "";?></textarea>
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
require(PUBLIC_PATH . '/layout.php');?>
