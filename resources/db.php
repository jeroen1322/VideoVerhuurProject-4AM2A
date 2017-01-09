<?php
function connectDB(){
  //Server gegevens
  $server = "localhost";
  $username = "root2";
  $passw = "root";
  $dbname = "tempovideo";
  $mysqli = new mysqli($server, $username, $passw);

  //Connectie met DB maken
  if($mysqli->connect_error){
    echo ("ERROR: " . $mysqli->connect_error);
    die();
  }
}
