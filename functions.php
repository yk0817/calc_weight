<?php


function db_con(){
  try {
    $pdo = new PDO('mysql:dbname=lose_weight;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnecError:'.$e->getMessage());
  }

}



 ?>
