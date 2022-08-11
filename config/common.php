<?php
if($_SERVER['REQUEST_METHOD'] === '$_POST'){
    if(!hash_equals($_SESSION['token'],$_POST['token'])){
        echo "Invalid Token!";
        die();
    }else{
        unset($_SESSION['token']);
    }
}
if(empty($_SESSION['token'])){
    if(function_exists('random_bytes')){
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }else{
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
function escape($html){
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}