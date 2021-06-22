<?php
function randomString(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return $characters[rand(0, strlen($characters))];
}
?>
