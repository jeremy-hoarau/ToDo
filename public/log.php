<?php
    require_once('../private/config.php');
    if(isset($_SESSION['id']))
        redirect_to("index.php");
    $page_name = 'Login';

    $username = "";
    $password = "";

    ob_start();
    if (is_post_request()){
        $username = $_POST['username'] ? $_POST['username'] : '';
        $password = $_POST['password'] ? $_POST['password'] : '';

        if ($username != '') {
            $connexion = connect_db();
            $user = select_user_by_pseudo($connexion, $username);
            if (password_verify($password, $user['password'])){
                $_SESSION['id'] = $user['id'];
                redirect_to("index.php");
            }
            else{
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                                Wrong Credentials!
                                </div>";
            }
            disconnect_db($connexion);
        }
        else{
            echo "<div class=\"alert alert-danger\" role=\"alert\">
                                The Fields cannot be empty!
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
                            <h3 class="text-center color-4">Login</h3>
                            <div class="form-group">
                                <label for="username" class="color-4">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control back-color-0 border-color-0"  value="<?php echo (isset($_POST['username']))? $_POST['username'] : "";?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="color-4">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control back-color-0 border-color-0">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md back-color-4 border-color-4 color-0" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <a href="<?php echo url_for('/sign.php') ?>" class="color-4">Register here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>