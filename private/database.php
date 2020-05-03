<?php
require_once(__ROOT__ . '/private/config_db.php');

function connect_db() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    return $connection;
}

function disconnect_db($connection) {
    if(isset($connection)) {
    mysqli_close($connection);
    }
}