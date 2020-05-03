<?php
?>
<!DOCTYPE html>
<html lang="en">
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href= <?php echo __ROOT__.'/public/home.php';?> >TODO</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href= <?php echo __ROOT__.'/public/home.php';?> >Home</a>
                </li>
            </ul>
            <ul class="navbar-nav justify-content-mr-end">
                <li class="nav-item" style="margin-right: 0">
                    <label class="switch">
                        <input type="checkbox" onclick="DarkMode()">
                        <span class="slider round"></span>
                    </label>
                </li>
                <li id="DarkModeText" class="nav-item nav-link" style="margin-left: 0">
                    Dark Mode
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Friends</a>
                </li>
                <li class="navbar-item active">
                    <svg class="bi bi-bell" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"style="margin-top:8px">
                        <path d="M8 16a2 2 0 002-2H6a2 2 0 002 2z"/>
                        <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 004 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 00-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 111.99 0A5.002 5.002 0 0113 6c0 .88.32 4.2 1.22 6z" clip-rule="evenodd"/>
                        <circle id="Notif" display="none" cx="12" cy="3" r="2.5" stroke="black" stroke-width="0.5" fill="red"/>
                    </svg>
                </li>
            </ul>
            <button type="button" class="btn btn-info my-2 my-lg-0" style="margin-left:30px; margin-right:30px;">Log In</button>
        </div>
    </nav>

    <body>
        <?php echo $content ?>
    </body>
</html>

<style>
    .navbar-nav > li{
        margin-left:30px;
        margin-right:30px;
    }
</style>

<script>
    function DarkMode() {
    }
</script>