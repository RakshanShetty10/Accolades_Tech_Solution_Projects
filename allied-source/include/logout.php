<?php
    session_start();

    unset($_SESSION['_id']);
    unset($_SESSION['_logged']);

    echo "<script>location.href='../login.php';</script>";

?>