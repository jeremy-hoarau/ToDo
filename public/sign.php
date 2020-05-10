<?php
require_once('../private/config.php');
$page_name = 'Sign In';

$username = "";
$email = "";
$password = "";
$confirmPassword = "";

ob_start();
if (is_post_request()){

    $username = $_POST['username'] ? $_POST['username'] : '';
    $email = $_POST['email'] ? $_POST['email'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '';
    $confirmPassword = $_POST['confirmPassword'] ? $_POST['confirmPassword'] : '';

    if ($username != '' && $email != '' && $password != '' && $confirmPassword != ''){
        $connexion = connect_db();
        if ($users = select_user_table($connexion)){
            $freeUsername = true;
            $freeEmail = true;
            while ($user = mysqli_fetch_assoc($users)){
                if ($user['pseudo'] == $username){
                    $freeUsername = false;
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                            The pseudo is already taken!
                            </div>";
                }
                if ($user['email'] == $email){
                    $freeEmail = false;
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                            The mail is already taken!
                            </div>";
                }
            }
        }
        mysqli_free_result($users);
        if ($freeUsername && $freeEmail){
            if($password == $confirmPassword){
                $result = add_new_user($connexion, $username, $email, $password);
                disconnect_db($connexion);
//                $message = "Pouette pouette";
//                echo $email;
//                mail($email, 'Pouette', $message);
                redirect_to("index.php");
            }
            else{
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Passwords don't match!
                            </div>";

            }
        }

    }
    else{
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Fields cannot be empty!
                            </div>";
    }

}

?>

    <div id="login" style="margin-top: 60px">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center color-4">Sign In</h3>
                            <div class="form-group">
                                <label for="username" class="color-4">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control back-color-0 border-color-0" value="<?php echo (isset($_POST['username']))? $_POST['username'] : "";?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="color-4">Email:</label><br>
                                <input type="email" name="email" id="email" class="form-control back-color-0 border-color-0" value="<?php echo (isset($_POST['email']))? $_POST['email'] : "";?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="color-4">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control back-color-0 border-color-0">
                            </div>
                            <div class="form-group">
                                <label for="password" class="color-4">Confirm Password:</label><br>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control back-color-0 border-color-0">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md back-color-4 border-color-4 color-0" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <label style="font-size: smaller">Already have an account?</label>
                                <a href="<?php echo url_for('/log.php') ?>" class="color-4">Log In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>