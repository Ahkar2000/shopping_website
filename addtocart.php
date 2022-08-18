<?php
session_start();
require "config/config.php";
require "config/common.php";
if($_POST){
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stat = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
    $stat->execute();
    $result = $stat->fetch(PDO::FETCH_ASSOC);

    if($qty > $result['quantity']){
        echo "<script>alert('Not enough stock!');window.location.href='product_detail.php?id=$id'</script>";
    }else{
        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
        }else{
            $_SESSION['cart']['id'.$id] = $qty;
        }
        header("location:cart.php");
    }
}