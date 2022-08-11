<?php 
require "../config/config.php";
require "../config/common.php";

$stat = $pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
$stat->execute();
header("location:categories.php");