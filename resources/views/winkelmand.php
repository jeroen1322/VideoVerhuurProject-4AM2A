<div class="panel panel-default">
  <div class="panel-body">
    <h1>WINKELMAND</h1>

<?php
if(!empty($_SESSION['login'])){
  $klant = $_SESSION['login']['0'];

  //Haal id op van Order op
  $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=?");
  $stmt->bind_param("i", $klant);
  $stmt->execute();

  $stmt->bind_result($order_id);
  $stmt->fetch();
  $stmt->close();


  //Haal exemplaarid van Orderregel dat bij de Order hoort op
  $or_stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
  $or_stmt->bind_param("i", $order_id);
  $or_stmt->execute();

  $or_stmt->bind_result($OR_id);
  $or_stmt->fetch();
  $or_stmt->close();

  //Haal de Filmid op van het exemplaar op
  $exm_stmt = DB::conn()->prepare("SELECT filmid FROM `Exemplaar` WHERE id=?");
  $exm_stmt->bind_param("i", $OR_id);
  $exm_stmt->execute();

  $exm_stmt->bind_result($exm_film_id);
  $exm_stmt->fetch();
  $exm_stmt->close();

  //Haal alles van de film op dat overeen komt met de filmid van het exemplaar
  $exm_film_stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` WHERE id=?");
  $exm_film_stmt->bind_param("i", $exm_film_id);
  $exm_film_stmt->execute();

  $exm_film_stmt->bind_result($film_id, $titel, $acteur, $omschr, $genre, $img);
  $exm_film_stmt->fetch();
  $exm_film_stmt->close();

  echo "FILM ID: " . $film_id . "<br>";
  echo "TITEL: " . $titel . "<br>";

  DB::conn()->close();



}else{
  echo "LOG IN!";
}

?>
  </div>
</div>
