<?php
    require('../private/config.php');
    if(!isset($_SESSION['id']))
        redirect_to("log.php");

    $con = connect_db();
    ob_start();

    if(is_get_request() && isset($_GET['id']) && $_GET['id'] != '' && (user_has_list_by_ids($con, $_SESSION['id'], $_GET['id']) || get_user_list_access($con, $_SESSION['id'], $_GET['id']) == 2)){
        $list = select_list_by_id($con, $_GET['id']);
        $list_name = $list['name'];
        $list_description = $list['description'];
        $page_name = 'Edit list - ' . htmlspecialchars($list['name']);
    }
    if(is_post_request() && (user_has_list_by_ids($con, $_SESSION['id'], $_GET['id']) || get_user_list_access($con, $_SESSION['id'], $_GET['id']) == 2)){
        $list_name = $_POST['name'] ? $_POST['name'] : '';
        $list_description = $_POST["description"] ? $_POST["description"] : '';

        if ($list_name != ''){
            $connexion = connect_db();
            $result = update_list($connexion, $_GET['id'], $list_name, $list_description);
            mysqli_free_result($result);
            redirect_to("index.php");
        }
        if ($list_name == ''){
            echo "<div class=\"alert alert-danger\" role=\"alert\">
                                The name cannot be empty!
                                </div>";
        }
    }
    disconnect_db($con);

    ?>

        <!-- Previous Page Button -->
        <a href="<?php echo url_for('/index.php')?>">
            <button class="btn back-color-0 border-color-0 color-4" style="position: absolute; margin: 20px">
                <svg class="bi bi-arrow-left" width="2em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5.854 4.646a.5.5 0 010 .708L3.207 8l2.647 2.646a.5.5 0 01-.708.708l-3-3a.5.5 0 010-.708l3-3a.5.5 0 01.708 0z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M2.5 8a.5.5 0 01.5-.5h10.5a.5.5 0 010 1H3a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
                </svg>
            </button>
        </a>

        <div style="margin-top:60px">
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <form class="form" action="" method="post">
                                <h3 class="text-center color-4">Edit list: <?php echo htmlspecialchars($list_name)?></h3>
                                <div class="form-group">
                                    <label for="name" class="color-4">List name:</label><br>
                                    <input type="text" name="name" id="name" class="form-control back-color-0 border-color-0"" value="<?php echo htmlspecialchars($list_name);?>">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="color-4">Description</label>
                                    <textarea class="form-control back-color-0 border-color-0" name="description" id="description" rows="4" style="text-align: left"><?php echo htmlspecialchars($list_description)?></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-info btn-md back-color-4 border-color-4 color-0" value="submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <?php $content = ob_get_clean();
    require(PUBLIC_PATH . '/layout.php'); ?>