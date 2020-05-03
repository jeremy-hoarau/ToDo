<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/private/database.php');
require_once(__ROOT__ . '/private/query.php');

$username = "";
$email = "";
$password = "";
$confirmPassword = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['username'] ? $_POST['username'] : '';
    $email = $_POST['email'] ? $_POST['email'] : '';
    $password = $_POST['password'] ? $_POST['password'] : '';
    $confirmPassword = $_POST['confirmPassword'] ? $_POST['confirmPassword'] : '';

    if ($username != '' && $email != '' && $password != '' && $confirmPassword != ''){
        $connection = connect_db();
        if ($users = select_user_table($connection)){
            $freeUsername = true;
            $freeEmail = true;
            while ($user = mysqli_fetch_assoc($users)){
                if ($user['pseudo'] == $username){
                    $freeUsername = false;
                    echo "Le pseudo est déjà pris <br>";
                }
                if ($user['email'] == $email){
                    $freeEmail = false;
                    echo "Le mail est déjà pris <br>";
                }
            }
        }
        mysqli_free_result($users);
        if ($freeUsername && $freeEmail){
            if($password == $confirmPassword){
                $result = add_new_user($connection, $username, $email, $password);
                var_dump($result);
            }
        }

    }
    else{
        echo "faut tout remplir !";
    }

}

?>

<?php
require_once(__ROOT__. '/config.php');
ob_start();?>

    <body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Sign In form</h3>
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
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Confirm Password:</label><br>
                                <input type="text" name="confirmPassword" id="confirmPassword" class="form-control">
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