<?php
// This code is based on the assignment PHP : Sessions, by Robert Smith;
session_start();
$_SESSION = [];
session_destroy();
header("Location: ../public/index.php");
exit();

