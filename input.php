<?php

$weight = $_POST["weight"];
echo $weight;

try {
  $pdo = new PDO('mysql:dbname=lose_weight;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnecError:'.$e->getMessage());
}

$stmt = $pdo->prepare('INSERT INTO weight(date,weight) VALUES (CURDATE(),:weight)');
// $stmt = $pdo->prepare('INSERT INTO weight(weight) VALUES (:weight)');

$stmt->bindValue(':weight',$weight,PDO::PARAM_INT);
$status = $stmt->execute();

// チェック

if ($status == false) {
  db_error($stmt);
}else {
  header("Location:excersise2.php");
  exit;
}







 ?>
