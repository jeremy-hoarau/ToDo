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
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_user_by_id($connection, $id){
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_user_by_email($connection, $email){
    $email = db_escape($connection, $email);
    $query = "SELECT * FROM `user` ";
    $query .= "WHERE email = '".$email."';";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $user;
}

function select_tasks_by_list_by_id($connection, $id)
{
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE todo_id = '".$id."';";
    $result = mysqli_query($connection, $query);
    return $result;
}

function select_list_by_id($connection, $id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id = '".$id."';";
    $result = mysqli_query($connection, $query);
    $list = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $list;
}

function select_lists_by_user_id($connection, $id)
{
    $id = db_escape($connection, $id);
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE creator_id = '".$id."';";
    return mysqli_query($connection, $query);
}

function select_shared_lists_by_user_id($connection, $id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id IN ";
    $query .= "(SELECT todo_id FROM `user_has_todo` ";
    $query .= "WHERE user_id = '".$id."' AND (authorised = '1' OR authorised = '2') AND accepted = '1');";
    return mysqli_query($connection, $query);
}

function user_has_list_by_ids($connection, $user_id, $list_id)
{
    $query = "SELECT * FROM `todo` ";
    $query .= "WHERE id = ".$list_id." ";
    $query .= "AND creator_id = ".$user_id.";";
    $result = mysqli_query($connection, $query);
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
    $query = "DELETE FROM todo ";
    $query .= "WHERE id = ".$id.";";
    mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}

function clear_todo_by_id($connection, $id) {
    $query = "DELETE FROM task ";
    $query .= "WHERE todo_id = ".$id.";";
    return mysqli_query($connection, $query);
}

function clear_todo_from_user_by_id($connection, $list_id) {
    $query = "DELETE FROM user_has_todo ";
    $query .= "WHERE todo_id = ".$list_id.";";
    return mysqli_query($connection, $query);
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

function get_user_list_access($connection, $user_id, $list_id){
    $query = "SELECT * FROM `user_has_todo` ";
    $query .= "WHERE todo_id = '" . $list_id . "' AND user_id = '" . $user_id . "' AND accepted = '1';";
    $result = mysqli_query($connection, $query);
    $access = mysqli_fetch_assoc($result);
    if ($access == null) {
        mysqli_free_result($result);
        return 0;
    }
    mysqli_free_result($result);
    return $access['authorised'];
}

function create_new_task($connection,$task_todo, $task_name, $task_state, $task_description){
    $todo = db_escape($connection, $task_todo);
    $name = db_escape($connection, $task_name);
    $description = db_escape($connection, $task_description);
    $query = "INSERT INTO `task` (todo_id, name, state, description ) ";
    $query .= "VALUES ('". $todo."', '".$name."', '".$task_state."', '".$description."');";
    return mysqli_query($connection, $query);
}

function update_task($connection, $task_id, $task_name, $task_state, $task_description)
{
    $name = db_escape($connection, $task_name);
    $description = db_escape($connection, $task_description);
    $query = "UPDATE `task` ";
    $query .= "SET name = '" . $name . "', state = '" . $task_state . "', description = '" . $description . "' ";
    $query .= "WHERE id = " . $task_id . " ;";
    return mysqli_query($connection, $query);
}

function update_list($connection, $list_id, $list_name, $list_description)
{
    $name = db_escape($connection, $list_name);
    $description = db_escape($connection, $list_description);
    $query = "UPDATE `todo` ";
    $query .= "SET name = '" . $name . "', description = '" . $description . "' ";
    $query .= "WHERE id = " . $list_id . " ;";
    return mysqli_query($connection, $query);
}

function user_has_task($connection, $task_id, $user_id, $list_id){
    $access = get_user_list_access($connection, $user_id, $list_id);
    if(($access == null || $access == 0) && !user_has_list_by_ids($connection, $user_id, $list_id))
        return false;
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE todo_id = '".$list_id."' ";
    $query .= "AND id = '". $task_id ."';";
    $result = mysqli_query($connection, $query);
    $has_task = mysqli_fetch_array($result);
    if($has_task == null)
    {
        mysqli_free_result($result);
        return false;
    }
    mysqli_free_result($result);
    return true;
}

function select_task_by_id($connection, $task_id)
{
    $id = db_escape($connection, $task_id);
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE id = " . $id . " ;";
    return mysqli_query($connection, $query);
}

function delete_task_by_id($connection, $task_id){
    $query = "DELETE FROM `task` ";
    $query .= "WHERE id = '".$task_id."';";
    mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}

function select_done_tasks_by_list_id($con, $list_id){
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE todo_id = '".$list_id."' ";
    $query .= "AND state = '1';";
    return mysqli_query($con, $query);
}

function select_in_progress_tasks_by_list_id($con, $list_id){
    $query = "SELECT * FROM `task` ";
    $query .= "WHERE todo_id = '".$list_id."' ";
    $query .= "AND state = '0';";
    return mysqli_query($con, $query);
}

function update_task_state($connection, $task_id, $state){
    $query = "UPDATE `task` ";
    $query .= "SET state = ".$state. " ";
    $query .= "WHERE id = ".$task_id.";";
    mysqli_query($connection, $query);
    return mysqli_affected_rows($connection);
}

function select_user_has_todo_by_todo_id($connection, $todo_id){
    $id = db_escape($connection, $todo_id);
    $query = "SELECT * FROM `user_has_todo` ";
    $query .= "WHERE todo_id = " . $id . " ;";
    return mysqli_query($connection, $query);
}

function update_user_has_to_do_state($connection, $row_id, $state){
    $id = db_escape($connection, $row_id);
    $query = "UPDATE `user_has_todo` ";
    if($state == 0)
    {
        $query .= "SET (authorised, accepted) = (". $state .", '0') ";
        $query .= "WHERE id = ". $id . " ;";
    }
    else
    {
        $query .= "SET authorised = ". $state ." ";
        $query .= "WHERE id = ". $id . " ;";
    }
    return mysqli_query($connection, $query);
}

function user_accept_todo($connection, $todo_id, $user_id){
    $query = "UPDATE `user_has_todo` ";
    $query .= "SET accepted = '1' ";
    $query .= "WHERE todo_id = '". $todo_id . "' AND user_id = '".$user_id."';";
    mysqli_query($connection, $query);
    if(mysqli_affected_rows($connection) >= 1)
        return true;
    return false;
}

function user_refuse_todo($connection, $todo_id, $user_id){
    $query = "DELETE FROM `user_has_todo` ";
    $query .= "WHERE todo_id = '". $todo_id . "' AND user_id = '".$user_id."';";
    mysqli_query($connection, $query);
    if(mysqli_affected_rows($connection) >= 1)
        return true;
    return false;
}

function delete_user_has_todo_by_id($connection, $row_id){
    $id = db_escape($connection, $row_id);
    $querry = "DELETE FROM `user_has_todo` ";
    $querry .= "WHERE id = ". $id . " ;";
    return mysqli_query($connection, $querry);
}

function user_has_already_todo_access($connection, $user_id, $todo_id){
    $user = db_escape($connection, $user_id);
    $todo = db_escape($connection, $todo_id);
    $query = "SELECT * FROM `user_has_todo` ";
    $query .= "WHERE user_id = ". $user. " AND todo_id = ". $todo. " ; ";
    return mysqli_query($connection, $query);
}

function create_new_user_has_to_do($connection, $user_id, $todo_id, $state){
    $user = db_escape($connection, $user_id);
    $todo = db_escape($connection, $todo_id);
    $result= user_has_already_todo_access($connection, $user, $todo);
    if (mysqli_num_rows($result)!= 0){
        return false;
    }
    $query = "INSERT INTO `user_has_todo` (todo_id, user_id, authorised, accepted) ";
    $query .= "VALUES ('". $todo."', '".$user."', '".$state."', '0');";
    return mysqli_query($connection, $query);
}

function has_list_request($connection, $user_id){
    $query = "SELECT * FROM `user_has_todo` ";
    $query .= "WHERE user_id = '".$user_id."' AND accepted = '0';";
    $result = mysqli_query($connection, $query);
    $has_request = mysqli_fetch_array($result);
    if($has_request == null)
    {
        mysqli_free_result($result);
        return false;
    }
    mysqli_free_result($result);
    return true;
}

function get_todo_invitations($connection, $user_id){
    $query = "SELECT * FROM `user_has_todo` ";
    $query .= "WHERE user_id = '".$user_id."' AND accepted = '0';";
    return mysqli_query($connection, $query);
}