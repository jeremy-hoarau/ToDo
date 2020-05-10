<?php
require_once('../private/config.php');
$page_name = 'List access';
$con = connect_db();
if(!isset($_GET['list_id']) || !isset($_SESSION['id']) || !user_has_list_by_ids($con, $_SESSION['id'], $_GET['list_id']))
{
    redirect_to('index.php');
    disconnect_db($con);
}
$list_id = $_GET['list_id'] ? $_GET['list_id'] : '';
ob_start();

$todo_content = "
    <!-- Previous Page Button -->
    <a href='" . url_for('/todo_list.php?id=') . $list_id ."'>
        <button class=\"btn back-color-0 border-color-0 color-4\" style=\"position: absolute; margin: 20px\">
            <svg class=\"bi bi-arrow-left\" width=\"2em\" height=\"1.5em\" viewBox=\"0 0 16 16\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\">
                <path fill-rule=\"evenodd\" d=\"M5.854 4.646a.5.5 0 010 .708L3.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z\" clip-rule=\"evenodd\"/>
                <path fill-rule=\"evenodd\" d=\"M2.5 8a.5.5 0 01.5-.5h10.5a.5.5 0 010 1H3a.5.5 0 01-.5-.5z\" clip-rule=\"evenodd\"/>
            </svg>
        </button>
    </a>";
$todo_content .= "<div class=\"text-center\">";
$todo_content .= "<div class=\"badge badge-pill badge-secondary color-4 back-color-0\" style=\"font-size: xx-large; margin-top: 2%; margin-bottom: 2%;\">Manage access todo list</div>";
$todo_content .= "</div>";
$todo_content .= "<div class=\"container-fluid\">";
$todo_content .= "<div class=\"row back-color-0\" style='min-height: 600px;'>";
$todo_content .= "<div class=\"col-6 back-color-0\">";
$todo_content .= "<div class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
$todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center; margin-top: 25px\">Friend who have access</h3>";
echo $todo_content;

if ($list_id != ''){
    $connexion = connect_db();
    $access = select_user_has_todo_by_todo_id($connexion, $list_id);
    $todo_content = "<div class=\"container list-group-item  back-color-0\">";
    while ($row = mysqli_fetch_assoc($access)){
        $friend = select_user_by_id($connexion, $row['user_id']);
        $todo_content .= "<form action=\"\" method=\"post\" class=\"row justify-content-between back-color-1\" style='padding:1%'>";
        $todo_content .="<div class=\"col-2 color-4\">".htmlspecialchars($friend['pseudo'])."</div>";
        $todo_content .="<select class=\"col-3 form-control back-color-1 border-color-4 color-4\" name=\"state\" id=\"state\" >";
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

    $todo_content = "<div class=\"col-6 back-color-0\" style='margin-top: 25px'>";
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
