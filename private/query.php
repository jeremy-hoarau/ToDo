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

function select_user_by_email($connection, $email){
    $email = db_escape($connection, $email);
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE email = '".$email."';";
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
    $id = db_escape($connection, $id);
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

function has_friend_request($connection, $id){
    $query = "SELECT * FROM `user_has_user` ";
    $query .= "WHERE friend_id = ".$id." AND accepted = 0;";
    $result = mysqli_query($connection, $query);
    $has_pending = mysqli_fetch_array($result);
    if($has_pending == null)
    {
        mysqli_free_result($result);
        return false;
    }
    mysqli_free_result($result);
    return true;
}

function are_friends_or_pending($connection, $user_id, $friend_id){
    $query = "SELECT * FROM `user_has_user` ";
    $query .= "WHERE (user_id = ".$user_id." AND friend_id = ".$friend_id.") ";
    $query .= "OR (user_id = ".$friend_id." AND friend_id = ".$user_id.");";
    $result = mysqli_query($connection, $query);
    $are_friends = mysqli_fetch_array($result);
    if($are_friends == null)
    {
        mysqli_free_result($result);
        return false;
    }
    mysqli_free_result($result);
    return true;
}

function select_friends($connection, $user_id, $accepted){
    $query = "SELECT * FROM `user_has_user` ";
    $query .= "WHERE friend_id = ".$user_id." AND accepted = ".$accepted.";";
    return mysqli_query($connection, $query);
}

function select_pending_friends($connection, $user_id){
    $query = "SELECT * FROM `user_has_user` ";
    $query .= "WHERE user_id = ".$user_id." AND accepted = '0';";
    return mysqli_query($connection, $query);
}

function update_friend_status($connection, $user_id, $friend_id, $accepted){
    $query = "UPDATE `user_has_user` ";
    $query .= "SET accepted = ".$accepted. " ";
    $query .= "WHERE user_id = ".$friend_id." AND friend_id = ". $user_id. ";";
    $query1 = "INSERT INTO `user_has_user` ";
    $query1 .= "(user_id, friend_id, accepted) ";
    $query1 .= "VALUES ('".$user_id."', '".$friend_id."', '".$accepted."');";
    mysqli_query($connection, $query);
    return mysqli_query($connection, $query1);
}

function delete_friend($connection, $user_id, $friend_id){
    $query = "DELETE FROM `user_has_user` ";
    $query .= "WHERE user_id = '". $user_id ."' AND friend_id = '". $friend_id . "';";
    $query1 = "DELETE FROM `user_has_user` ";
    $query1 .= "WHERE user_id = '". $friend_id ."' AND friend_id = '". $user_id . "';";
    mysqli_query($connection, $query);
    return mysqli_query($connection, $query1);
}

function add_new_friend($connection, $user_id, $friend_id){
    $query = "INSERT INTO `user_has_user` (user_id, friend_id, accepted) ";
    $query .= "VALUES ('". $user_id."', '".$friend_id."', '0');";
    return mysqli_query($connection, $query);
}

function add_new_list($connection, $name, $description, $creator_id){
    $name = db_escape($connection, $name);
    $description = db_escape($connection, $description);
    $query = "INSERT INTO `todo` (name, creator_id, description) ";
    $query .= "VALUES ('". $name."', '".$creator_id."', '".$description."');";
    return mysqli_query($connection, $query);
}

function create_new_task($connection,$task_todo, $task_name, $task_state, $task_description){
    $todo = db_escape($connection, $task_todo);
    $name = db_escape($connection, $task_name);
    $description = db_escape($connection, $task_description);
    $query = "INSERT INTO `task` (todo_id, name, state, description ) ";
    $query .= "VALUES ('". $todo."', '".$name."', '".$task_state."', '".$description."');";
    return mysqli_query($connection, $query);
}