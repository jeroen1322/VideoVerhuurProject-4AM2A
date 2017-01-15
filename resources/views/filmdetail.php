<div class="row">
    <div class="col-md-10 col-md-offset-1 details">
<?php

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM Film WHERE titel=?");
$stmt->bind_param("s", $this->filmNaam);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();

$cover = "/cover/" . $img;
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);

if(!empty($_GET)){

  $ophaalDatum = "13-01-2017";
  $afleverDatum = "20-01-2017";

  $_SESSION['cart_item'] = array();
  $_SESSION['cart_item']['id'] = $_GET['code'];
  $product_cart_id = $_SESSION['cart_item']['id'];
  // echo $product_cart_id;

  //VOEG TO AAN `ORDER`
  $order_id = rand(1, 2100);
  $bedrag = 7.5; 
  $klant = $_SESSION['login']['0'];

  $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, bedrag) VALUES (?, ?, ?)");
  $cart_stmt->bind_param("iid", $order_id, $klant, $bedrag);
  $cart_stmt->execute();
  $cart_stmt->close();

  //VOEG TOE AAN `ORDERREGEL`
  // $exemplaar_id = 1;
  $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=?");
  $exm_stmt->bind_param("i", $id);
  $exm_stmt->execute();

  $exm_stmt->bind_result($exemplaar_id);
  $exm_stmt->fetch();
  $exm_stmt->close();
  //
  $or_stmt = DB::conn()->prepare("INSERT INTO `Orderregel` (exemplaarid, orderid) VALUES (?, ?)");
  $or_stmt->bind_param("ii", $exemplaar_id, $order_id);
  $or_stmt->execute();
  $or_stmt->close();

  echo "<div class='succes'>FILM TOEGEVOEGD AAN UW <a href='/winkelmand'>WINKELMAND</a></div>";
}
DB::conn()->close();

if(!empty($id)){
?>
      <a class="btn btn-success terug_button" href="/film/aanbod">
        <li class="fa fa-arrow-left filmaanbod-terug"></li>Filmaanbod
      </a>
        <div class="filmDetails">
          <div class="panel panel-default">
            <div class="panel-body">
              <img src="<?php echo $cover ?>" class="img-responsive cover"/>
              <h1><b><?php echo $titel ?></b></h1>
              <h3>Omschrijving</h3>
              <p><?php echo $omschr ?></p>
              <h3>Acteurs</h3>
              <p><?php echo $acteur ?></p>
              <h3>Genre</h3>
              <p><?php echo $genre ?></p>
              <form method="post" action="?action=add&code=<?php echo $id ?>">
                <input type="submit" class="btn btn-success bestel" value="Bestel">
              </form>
            </div>
          </div>
      </div>
    </div>
</div>
<?php
}else{
  echo "404 - FILM NIET GEVONDEN";
}
