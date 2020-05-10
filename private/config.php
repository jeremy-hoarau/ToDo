<?php
    define("PRIVATE_PATH", dirname(__FILE__));
    define("PROJECT_PATH", dirname(PRIVATE_PATH));
    define("PUBLIC_PATH", PROJECT_PATH . '/public');

    define('GMailUSER', 'todolist.2phpd@gmail.com');
    define('GMailPWD', 'PouettePouette1234');

    session_save_path(PRIVATE_PATH. '/sessions');
    session_start();

    $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
    $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
    define("WWW_ROOT", $doc_root);

    require_once('functions.php');
    require_once(PRIVATE_PATH . '/database.php');
    require_once(PRIVATE_PATH.'/query.php');

