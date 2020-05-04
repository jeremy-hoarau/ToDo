<?php
require_once(PRIVATE_PATH . '/database.php');

function select_user_table($connection){
    $query = "SELECT * FROM `user` ";
    return mysqli_query($connection, $query);
}

function select_user_by_pseudo($connection, $pseudo){
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE pseudo = '".$pseudo."';";
    return mysqli_query($connection, $query);
}

function add_new_user($connection, $username, $email, $password){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO `user` (pseudo, email, password) ";
    $query.= "VALUES ('". $username."', '".$email."', '".$password."');";
    echo $query . "<br>";
    return mysqli_query($connection, $query);
}
