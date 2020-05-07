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
                            Le pseudo est déjà pris!
                            </div>";
                }
                if ($user['email'] == $email){
                    $freeEmail = false;
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Le mail est déjà pris!
                            </div>";
                }
            }
        }
        mysqli_free_result($users);
        if ($freeUsername && $freeEmail){
            if($password == $confirmPassword){
                $result = add_new_user($connexion, $username, $email, $password);
                mysqli_free_result($result);
                disconnect_db($connexion);
                redirect_to("index.php");
            }
            else{
                echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Les mots de passe ne sont pas identiques!
                            </div>";

            }
        }

    }
    else{
        echo "<div class=\"alert alert-danger\" role=\"alert\">
                            Il faut remplir tous les champs!
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
                            <h3 class="text-center text-info">Sign In</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control" value="<?php echo (isset($_POST['username']))? $_POST['username'] : "";?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo (isset($_POST['email']))? $_POST['email'] : "";?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Confirm Password:</label><br>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <label style="font-size: smaller">Already have an account?</label>
                                <a href="<?php echo url_for('/log.php') ?>" class="text-info">Log In</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>