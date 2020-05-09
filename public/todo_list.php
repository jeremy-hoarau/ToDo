<?php
    require('../private/config.php');
    if(!isset($_SESSION['id']))
        redirect_to("log.php");
    ob_start();

    if(is_get_request() && isset($_GET['id']))
    {
        $con = connect_db();
        // todo_list
        $list = select_list_by_id($con, $_GET['id']);
        $page_name = $list['name'];
        if(user_has_list_by_ids($con, $_SESSION['id'], $_GET['id']))
            $list_access = 2;
        else
            $list_access = get_user_list_access($con, $_SESSION['id'], $_GET['id']);
        //0 -> no access
        //1 -> read access
        //2 -> all access
        if($list_access == 0)
            redirect_to('index.php');
        ?>
        <div class="text-center">
            <div class="badge badge-pill badge-secondary color-4 back-color-0" style="font-size: xx-large; margin-top: 30px">
                Todo List : <?php echo htmlspecialchars($list['name']);?>
            </div>
        </div>
        <div style="margin-top:50px; margin-left: 20px;">
            <a class="btn color-0 back-color-4 border-color-4" href="<?php echo url_for('/create_task.php?id='.$_GET['id']); ?>" style="width: 150px; height: 50px;"><p style="margin-top:5px">+ New Task</p></a>
        </div>
        <div class="container-fluid back-color-0"  style="margin-top: 20px; text-align: center; min-height:500px;">
            <div class="row">
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            In Progress:
                        </div>
                    </div>
                </div>
                <div class="col"  style="margin: 30px;">
                    <div class="text-center">
                        <div class="badge badge-pill badge-secondary color-0 back-color-4" style="font-size: large; width:150px; height: 30px">
                            Done:
                        </div>
                    </div>
                    <?php if($list_access != 1){ echo "
                    <div style='position: absolute; right: 25px'>
                        <button type='button' class='btn btn-danger' onclick='DeleteAllDoneTasks()'>Delete All Done</button>
                    </div>
                    ";} ?>
                </div>
            </div>
            <div class="row">
                <?php display_tasks($con, $_GET['id'], $list_access);?>
            </div>
        </div>
        <?php
        disconnect_db($con);
    }
    else
    {
        redirect_to('index.php');
    }
?>

<script>
    function DeleteAllDoneTasks()
    {

    }
</script>


<?php $content = ob_get_clean();
require(PUBLIC_PATH . '/layout.php'); ?>