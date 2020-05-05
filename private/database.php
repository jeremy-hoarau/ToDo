<?php
    require_once(PRIVATE_PATH . '/config_db.php');

    function connect_db() {
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        confirm_db_connect();
        return $connection;
    }

    function disconnect_db($connection) {
        if(isset($connection)) {
            mysqli_close($connection);
        }
    }

    function db_escape($connection, $string) {
        return mysqli_real_escape_string($connection, $string);
    }

    function confirm_db_connect() {
        if(mysqli_connect_errno()) {
            $msg = "Database connection failed: ";
            $msg .= mysqli_connect_error();
            $msg .= " (" . mysqli_connect_errno() . ")";
            exit($msg);
        }
    }

    function confirm_result_set($result_set) {
        if (!$result_set) {
            exit("Database query failed.");
        }
    }