<?php
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: http://samesite-b.internal:8000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Cookie');
echo $_COOKIE['test'];
?>
