<?php
    function url_for($script_path) {
        if($script_path[0] != '/') {
            $script_path = "/" . $script_path;
        }
        return WWW_ROOT . $script_path;
    }

    function redirect_to($location) {
        header("Location: " . $location);
        exit;
    }

    function is_post_request() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    function is_get_request() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }


    //index
    function display_lists($list_request)
    {
        if (isset($_SESSION['id'])) {
            $con = connect_db();
            if($list_request === 'all')
                $lists = select_lists_by_user_id($con, $_SESSION['id']);
            else
                $lists = select_shared_lists_by_user_id($con, $_SESSION['id']);
            while ($list = mysqli_fetch_array($lists)) {
                $todo_content = '<div id="List-'. $list['id'].'" class="card back-color-1" style="margin-bottom: 50px;">
                                        <div class="card-header back-color-3 color-0" style="font-size: x-large">
                                            ' . htmlspecialchars($list['name']) . '
                                        </div>
                                        <div class="container-fluid" style="height: 140px; margin-bottom: 10px">
                                            <div class="row align-items-center">
                                                <div class="col color-4 back-color-0" style="text-align: justify; height: 120px; margin:20px; overflow: auto">
                                                    ' . htmlspecialchars($list['description']) . '
                                                </div>
                                                <div class="col-2">
                                                    <div class="row justify-content-md-center" style="margin:25px">
                                                        <a href="'. url_for('/todo_list.php?id=') . $list['id'] .'" class="btn btn-info">Manage</a>
                                                    </div>
                                                    '; if($list_request === 'all'){$todo_content .=
                                                    '<div class="row justify-content-md-center" style="margin:25px">
                                                        <button type="button" class="btn btn-danger" onclick="DeleteList('. $list['id'] .')">Delete</button>
                                                    </div>';} $todo_content .=
                                                '</div>
                                            </div>
                                        </div>
                                    </div>';
                echo $todo_content;
            }
            mysqli_free_result($lists);
            disconnect_db($con);
        }
    }
?>

<script>
    function DeleteList(id)
    {
        $.post( "delete_todo.php", { id: id},
            function(data, status) {
                console.log("deleted");
                console.log(status);
                if(status === 'success')
                    $('#List-' + id).css("display", "none");
        });
    }
</script>

