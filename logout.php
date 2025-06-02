<?php
session_start();
session_unset();
$_SESSION = array();
session_destroy();
header("Location: index.html");
exit();
?>
