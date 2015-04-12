<?php


// Minimum Deposit 
$minimumdeposit = 50;

// mySQL connection PDO
try {
  $dns = 'mysql:host=localhost;dbname=xplane';
  $utilisateur = 'root';
  $motDePasse = '';
  $connection = new PDO( $dns, $utilisateur, $motDePasse );
  $connection->exec("SET CHARACTER SET utf8");
} catch ( Exception $e ) {
  echo "Connection � MySQL impossible : ", $e->getMessage();
  die();
}

?>