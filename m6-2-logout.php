<?php
session_start();
session_destroy();
header("Location: m6-2-login.php");
exit;
?>