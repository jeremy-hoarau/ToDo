<?php
require_once(PRIVATE_PATH . '/config_db.php');

function connect_db() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    return $connection;
}

function disconnect_db($connection) {
    if(isset($connection)) {
    mysqli_close($connection);
    }
}