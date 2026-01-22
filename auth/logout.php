<?php
session_start();
session_destroy();
header("Location:/nada/index.php");
exit;