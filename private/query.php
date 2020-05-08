<?php
require_once(PRIVATE_PATH . '/database.php');

function select_user_table($connection){
    $query = "SELECT * FROM `user` ";
    return mysqli_query($connection, $query);
}

function select_user_by_pseudo($connection, $pseudo){
    $pseudo = db_escape($connection, $pseudo);
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE pseudo = '".$pseudo."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_user_by_id($connection, $id){
    $id = db_escape($connection, $id);
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_lists_by_user_id($connection, $id)
{
    $id = db_escape($connection, $id);
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE creator_id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    return $result;
}

function select_shared_lists_by_user_id($connection, $id)
{
    $id = db_escape($connection, $id);
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id IN ";
    $query .= "(SELECT todo_id FROM `user_has_todo` ";
    $query .= "WHERE user_id = '".$id."'";
    $query .= "AND authorised = '1');";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    return $result;
}

function add_new_user($connection, $username, $email, $password){
    $username = db_escape($connection, $username);
    $email = db_escape($connection, $email);
    $password = db_escape($connection, password_hash($password, PASSWORD_DEFAULT));
    $query = "INSERT INTO `user` (pseudo, email, password) ";
    $query.= "VALUES ('". $username."', '".$email."', '".$password."');";
    return mysqli_query($connection, $query);
}