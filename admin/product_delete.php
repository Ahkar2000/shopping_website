<?php 
require "../config/config.php";
require "../config/common.php";

$stat = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stat->execute();
$result = $stat->fetch(PDO::FETCH_ASSOC);
$image = $result['image'];
unlink("images/".$image);

$stat = $pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$stat->execute();
header("location:index.php");