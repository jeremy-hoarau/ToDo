<?php
require_once('../private/config.php');
$page_name = 'List access';
// il n'y a que le créateur qui peut arriver sur cette page
$list_id = $_GET['list_id'] ? $_GET['list_id'] : '';
ob_start();

$todo_content = "<div class=\"text-center\">";
$todo_content .= "<div class=\"badge badge-pill badge-secondary color-4 back-color-0\" style=\"font-size: xx-large; margin-top: 2%; margin-bottom: 2%;\">Manage access todo list</div>";
$todo_content .= "</div>";
$todo_content .= "<div style=\"margin: 2%;\">";
$todo_content .= "<a class=\"btn color-0 back-color-4 border-color-4\" href=\"". url_for("todo_list.php?id=".$list_id) ."\" style=\"width: 150px; height: 50px;\"><p style=\"margin-top:5px\">Back to todo list</p></a>";
$todo_content .= "</div>";
$todo_content .= "<div class=\"container-fluid\">";
$todo_content .= "<div class=\"row back-color-0\">";
$todo_content .= "<div class=\"col-6 back-color-0\">";
$todo_content .= "<div class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
$todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">Friend who have access</h3>";
echo $todo_content;

if ($list_id != ''){
    $connexion = connect_db();
    $access = select_user_has_todo_by_todo_id($connexion, $list_id);
    $todo_content = "<div class=\"container list-group-item align-items-center back-color-0\">";
    while ($row = mysqli_fetch_assoc($access)){
        $friend = select_user_by_id($connexion, $row['user_id']);
        $todo_content .= "<form action=\"\" method=\"post\" class=\"row justify-content-between back-color-1\" style='padding: 1%;'>";
        $todo_content .="<div class=\"col-2 color-4\">".htmlspecialchars($friend['pseudo'])."</div>";
        $todo_content .="<select class=\" col-2 form-control back-color-1 border-color-4 color-4\" name=\"state\" id=\"state\" >";
        if ($row['authorised'] == 1){
            $todo_content .="<option class='color-4'>Read only</option>";
            $todo_content .="<option class='color-4'>Full access</option></select>";
        }
        elseif ($row['authorised'] == 2){
            $todo_content .="<option class='color-4'>Full access</option>";
            $todo_content .="<option class='color-4'>Read only</option></select>";
        }
        $todo_content .="<button type=\"submit\" value=".$row['id']." class=' col-1 btn btn-warning' name=\"modify\" style='max-width: none;'>Modify</button>";
        $todo_content .="<button type=\"submit\" value=".$row['id']." class=' col-1 btn btn-danger' name=\"remove\" style='max-width: none;'>Remove</button>";
        $todo_content .="</form>";
    }
    $todo_content .="</div></div></div>";
    echo $todo_content;

    // ----------------------------------------------------------------------------------------------------------

    $todo_content = "<div class=\"col-6 back-color-0\" >";
    $todo_content .= "<div class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
    $todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">Give access to friend</h3>";

    $friends = select_friends($connexion, $_SESSION['id'], 1);
    if (mysqli_num_rows($friends)!= 0) {
        while ($row = mysqli_fetch_assoc($friends)) {
            $user = select_user_by_id($connexion, $row["user_id"]);
            $already_have_access = user_has_already_todo_access($connexion, $user['id'], $list_id);
            if (mysqli_num_rows($already_have_access)== 0) {
                $todo_content .= "<form action=\"\" method=\"post\">";
                $todo_content .= "<div class=\"list-group-item back-color-1 color-4 \">";
                $todo_content .= "<div class=\"row justify-content-center\">";
                $todo_content .= "<p class=\"col\">" . htmlspecialchars($user['pseudo']) . "</p>";
                $todo_content .= "<button type='submit' value = " . htmlspecialchars($user['id']) . " class='btn btn-warning' name =\"read\" style='margin-right: 1%;'>Read Only</button>";
                $todo_content .= "<button type='submit' value = " . htmlspecialchars($user['id']) . " class='btn btn-success' name =\"full\" style='margin-right: 1%;'>Full Access</button>";
                $todo_content .= "</div></div>";
                $todo_content .= "</form>";
            }
        }
    }
    else{
        $todo_content .= "<div>You have no friend ...</div>";
    }

    mysqli_free_result($friends);
    $todo_content .= "</div></div></div></div>";
    echo $todo_content;
    disconnect_db($connexion);
}
if (is_post_request()){
    var_dump($_POST);
    $connexion = connect_db();
    foreach($_POST as $key=>$value){
        if($key == 'modify'){
            if ($_POST['state'] == "Full access"){
                $state = 2;
            }
            elseif ($_POST['state'] == "Read only"){
                $state = 1;
            }
            $result = update_user_has_to_do_state($connexion, $_POST['modify'], $state);
        }
        elseif ($key == 'remove'){
            $result = delete_user_has_todo_by_id($connexion, $_POST['remove']);
        }
        elseif ($key == 'full'){
            $result = create_new_user_has_to_do($connexion, $_POST['full'], $list_id, 2);
        }
        elseif ($key == 'read'){
            $result = create_new_user_has_to_do($connexion, $_POST['read'], $list_id, 1);
        }

    }
    disconnect_db($connexion);
    header("Refresh:0");
}

?>





<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>
