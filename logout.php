<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_destroy();
header('Location: /lodz-mapz/index.php');
exit;
