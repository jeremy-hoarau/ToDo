<?php
require_once(PRIVATE_PATH . '/database.php');

function select_user_table($connection){
    $query = "SELECT * FROM `user` ";
    return mysqli_query($connection, $query);
}

function select_user_by_pseudo($connection, $pseudo){
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE pseudo = '".$pseudo."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_user_by_id($connection, $id){
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function add_new_user($connection, $username, $email, $password){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO `user` (pseudo, email, password) ";
    $query.= "VALUES ('". $username."', '".$email."', '".$password."');";
    return mysqli_query($connection, $query);
}

