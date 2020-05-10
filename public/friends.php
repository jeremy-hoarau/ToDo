<?php
require_once('../private/config.php');

if(!isset($_SESSION['id']))
    redirect_to("log.php");
ob_start();
$page_name = 'Friends';

/*
 * 1- Bouton accepter
 * 2- Trouver un ami
 * -3 Ma liste d'amis
 */

// récupérer les demandes d'ami non acceptés
$connexion = connect_db();
$friendRequest = select_friends($connexion, $_SESSION['id'], 0);

$todo_content = "<div class=\"text-center\">";
$todo_content .= "<div class=\"badge badge-pill badge-secondary color-4 back-color-0\" style=\"font-size: xx-large; margin-top: 2%; margin-bottom: 2%;\">Friends</div>";
$todo_content .= "</div>";
$todo_content .= "<div class=\"container-fluid\">";
$todo_content .= "<div class=\"row\">";
$todo_content .= "<div class=\"col-6 back-color-0\">";
$todo_content .= "<div class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
$todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">New friend request</h3>";

while ($row = mysqli_fetch_assoc($friendRequest)) {
    $user = select_user_by_id($connexion, $row["user_id"]);
    $todo_content .= "<form action=\"\" method=\"post\">";
    $todo_content .= "<div class=\"list-group-item back-color-1 color-4 \">";
    $todo_content .= "<div class=\"row justify-content-center\">";
    $todo_content .= "<p class=\"col\">".htmlspecialchars($user['pseudo'])."</p>";
    $todo_content .= "<button type='submit' value = 'Accept' class='btn btn-success' name =".htmlspecialchars($user['pseudo'])." >Accept</button>";
    $todo_content .= "</div></div>";
    $todo_content .= "</form>";
}

mysqli_free_result($friendRequest);
$todo_content .= "</div></div>";
echo $todo_content;


// Trouver un ami via pseudo ou email
$todo_content = "<div class=\"col-6 back-color-0\">";
$todo_content .= "<form action=\"\" method=\"post\" class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
$todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">Find a user</h3>";
$todo_content .= "<div class=\"form-group\">";
$todo_content .= "<label for=\"username\" class=\"color-4\">Enter Username and email:</label><br>";
$todo_content .= "<input type=\"text\" name=\"friend\" id=\"friend\" class=\"form-control back-color-4 border-color-4\">";
$todo_content .= "</div>";
$todo_content .= "<button type=\"submit\" class=\"btn btn-primary back-color-2 border-color-2 color-4\">Submit</button>";
$todo_content .= "</form></div></div>";
echo $todo_content;

// Lister les demandes d'amis envoyées

$friends = select_pending_friends($connexion, $_SESSION['id']);
if (mysqli_num_rows($friends)!= 0) {
    $todo_content = "<div class=\"row\">";
    $todo_content .= "<div class=\"col-12 back-color-0\">";
    $todo_content .= "<div class=\"row list-group list-group-flush\" style=\"margin: 1%;\">";
    $todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">Pending Requests</h3>";
    while ($row = mysqli_fetch_assoc($friends)) {

        $user = select_user_by_id($connexion, $row["friend_id"]);
        $todo_content .= "<form action=\"\" method=\"post\">";
        $todo_content .= "<div class=\"list-group-item back-color-1 color-4 \">";
        $todo_content .= "<div class=\"row justify-content-center\">";
        $todo_content .= "<p class=\"col\">" . htmlspecialchars($user['pseudo']) . "</p>";
        $todo_content .= "</div></div>";
        $todo_content .= "</form>";
    }
    $todo_content .= "</div></div></div>";
    echo $todo_content;
}

// Lister les amis
$todo_content = "<div class=\"row\">";
$todo_content .= "<div class=\"col-12 back-color-0\">";
$todo_content .= "<div class=\"list-group list-group-flush\" style=\"margin: 1%;\">";
$todo_content .= "<h3 class=\"list-group-item back-color-4 color-0\" style=\"text-align: center;\">My Friends</h3>";

$friends = select_friends($connexion, $_SESSION['id'], 1);
if (mysqli_num_rows($friends)!= 0) {
    while ($row = mysqli_fetch_assoc($friends)) {

        $user = select_user_by_id($connexion, $row["user_id"]);
        $todo_content .= "<form action=\"\" method=\"post\">";
        $todo_content .= "<div class=\"list-group-item back-color-1 color-4 \">";
        $todo_content .= "<div class=\"row justify-content-center\">";
        $todo_content .= "<p class=\"col\">" . htmlspecialchars($user['pseudo']) . "</p>";
        $todo_content .= "<button type=\"submit\" value = \"Delete\" class=\"col-1 btn btn-danger\" name=\"".htmlspecialchars($user['pseudo'])."\">Delete</button>";
        $todo_content .= "</div></div>";
        $todo_content .= "</form>";
    }
}
else{
    $todo_content .= "<div>You have no friend ...</div>";
}
$todo_content .= "</div></div></div></div>";
echo $todo_content;

if (is_post_request()){
    foreach($_POST as $key=>$value)
    {
        $friend_pseudo = $key;
    }

    if ($_POST[$friend_pseudo] == 'Accept'){
        $result = select_user_by_pseudo($connexion, $friend_pseudo);
        $friend_id = $result['id'];
        $result = update_friend_status($connexion, $_SESSION['id'], $friend_id, 1);
    }
    elseif ($_POST[$friend_pseudo] == 'Delete'){
        $result = select_user_by_pseudo($connexion, $friend_pseudo);
        $friend_id = $result['id'];
        $result = delete_friend($connexion, $_SESSION['id'], $friend_id);
    }
    elseif (isset($_POST['friend']) && $_POST['friend'] != ''){
        $result = select_user_by_pseudo($connexion, $_POST['friend']);
        if($result == null)
            $result = select_user_by_email($connexion, $_POST['friend']);
        $friend_id = $result['id'];
        if($friend_id != $_SESSION['id'] && !are_friends_or_pending($connexion, $_SESSION['id'], $friend_id))
            $result = add_new_friend($connexion, $_SESSION['id'], $friend_id);
    }
    header("Refresh:0");
}
disconnect_db($connexion);
?>

<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php');?>
