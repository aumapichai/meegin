<?php
    session_start();
    session_destroy();
    header("Location: expire.php");
    exit();
?>