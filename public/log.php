<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/private/database.php');
require_once(__ROOT__ . '/private/query.php');

$username = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'] ? $_POST['username'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '';

    if ($username != '') {
        $connexion = connect_db();
        $result = select_user_by_pseudo($connexion, $username);
        $result = mysqli_fetch_assoc($result);
        if (password_verify($password, $result['password'])){
            echo "CONNECTION";
            header("Location: home.php");
        }
        else{
            echo "Wrong password...";
        }
        disconnect_db($connexion);
    }
    else{
        echo "Faut remplir les champs";
    }
}

?>

<?php
require_once(__ROOT__. '/config.php');
ob_start();?>

    <body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                            </div>
                            <div id="register-link" class="text-right">
                                <a href="#" class="text-info">Register here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>

<?php $content = ob_get_clean();
require(__ROOT__ . '/layout.php');?>