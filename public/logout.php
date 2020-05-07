<?php
require_once('../private/config.php');
$page_name = 'Logout';

if(isset($_SESSION['id']))
    unset($_SESSION['id']);

redirect_to("log.php");
