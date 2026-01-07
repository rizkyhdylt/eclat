<?php
session_start();
session_destroy();
header("Location:/nada/auth/login.php");
exit;