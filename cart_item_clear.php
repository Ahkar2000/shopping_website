<?php 
session_start();
unset($_SESSION['cart']['id'.$_GET['pid']]);
header("location:cart.php");