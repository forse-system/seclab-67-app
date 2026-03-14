<?php

$samesite = $_GET['samesite'];
if (!isset($samesite)) {
    echo 'samesite is required';
    exit;
};

setcookie('test', 'samesite_'.$samesite, [
    'samesite' => $_GET['samesite'], // strict, lax, noneが入る想定
    'secure' => true
]);
echo 'OK';
?>
