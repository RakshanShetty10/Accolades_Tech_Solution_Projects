<?php
    session_start();

    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    unset($_SESSION['login_date']);

    echo "<script>location.href='../index.php';</script>";
?>