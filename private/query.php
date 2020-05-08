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
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_tasks_by_list_by_id($connection, $id)
{
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE todo_id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    return $result;
}

function select_list_by_id($connection, $id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $list = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $list;
}

function select_lists_by_user_id($connection, $id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE creator_id = '".$id."';";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    return $result;
}

function select_shared_lists_by_user_id($connection, $id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id IN ";
    $query .= "(SELECT todo_id FROM `user_has_todo` ";
    $query .= "WHERE user_id = '".$id."'";
    $query .= "AND authorised = '1' ";
    $query .= "OR authorised = '2');";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    return $result;
}

function user_has_list_by_ids($connection, $user_id, $list_id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id = ".$list_id." ";
    $query .= "AND creator_id = ".$user_id.";";
    $result = mysqli_query($connection, $query);
    confirm_result_set($result);
    $has_list = mysqli_fetch_array($result)? true : false;
    mysqli_free_result($result);
    return $has_list;
}

function add_new_user($connection, $username, $email, $password){
    $username = db_escape($connection, $username);
    $email = db_escape($connection, $email);
    $password = db_escape($connection, password_hash($password, PASSWORD_DEFAULT));
    $query = "INSERT INTO `user` (pseudo, email, password) ";
    $query.= "VALUES ('". $username."', '".$email."', '".$password."');";
    return mysqli_query($connection, $query);
}

function delete_list_by_id($connection, $id){
    $query = "DELETE FROM `todo` ";
    $query .= "WHERE id = ".$id.";";
    mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}
