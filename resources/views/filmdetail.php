<div class="row">
    <div class="col-md-10 col-md-offset-1 details">
<?php
$film = $this->filmNaam;

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM Film WHERE titel=?");
$stmt->bind_param("s", $film);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();


$exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
$exm_stmt->bind_param("i", $id);
$exm_stmt->execute();
$exm_stmt->bind_result($exemplaar_id);
$beschikbaar = array();
while($exm_stmt->fetch()){
    $beschikbaar[] = $exemplaar_id;
}
$exm_stmt->close();
$count = count($beschikbaar);

$cover = "/cover/" . $img;
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);

if(!empty($_GET)){

  $_SESSION['cart_item'] = array();
  $_SESSION['cart_item']['id'] = $_GET['code'];
  $product_cart_id = $_SESSION['cart_item']['id'];
  // echo $product_cart_id;

  //VOEG TO AAN `ORDER`
  $order_id = rand(1, 2100);
  $bedrag = 7.50;
  $klant = $_SESSION['login']['0'];
  $besteld = 0;
  $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, bedrag, besteld) VALUES (?, ?, ?, ?)");
  $cart_stmt->bind_param("iidi", $order_id, $klant, $bedrag, $besteld );
  $cart_stmt->execute();
  $cart_stmt->close();

  //VOEG TOE AAN `ORDERREGEL`
  $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
  $exm_stmt->bind_param("i", $id);
  $exm_stmt->execute();
  $exm_stmt->bind_result($exemplaar_id);
  $exm_stmt->fetch();
  $exm_stmt->close();

  $exm_stmt = DB::conn()->prepare("UPDATE `Exemplaar` SET statusid=2 WHERE id=?");
  $exm_stmt->bind_param("i", $exemplaar_id);
  $exm_stmt->execute();
  $exm_stmt->close();

  $or_stmt = DB::conn()->prepare("INSERT INTO `Orderregel` (exemplaarid, orderid) VALUES (?, ?)");
  $or_stmt->bind_param("ii", $exemplaar_id, $order_id);
  $or_stmt->execute();
  $or_stmt->close();
  $e = str_replace(' ', '_', $titel);
  header("Refresh:0; url=/film/" . $e);
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
              <?php
              $dis = false;
              if($count >=4){
                ?>
                <p class='green_count'><i>NOG BESCHIKBAAR: <?php echo $count ?> </i></p>
                <?php
              }elseif($count <= 4 && $count > 1){
                ?>
                <p class='orange_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                <?php
              }elseif($count >=1){
                ?>
                <p class='red_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                <?php
              }elseif($count == 0){
                $dis = true;
                ?>
                <p class='red_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                  <?php
              }
              ?>
              <h3><b>Prijs</b></h3>
              <p><b>€7,50</b></p>
              <form method="post" action="?action=add&code=<?php echo $id ?>">
                <?php
                if($dis){
                  ?>
                  <input type="submit" class="btn btn-success bestel" value="Bestel" disabled>
                  <?php
                }else{
                  ?>
                  <input type="submit" class="btn btn-success bestel" value="Bestel">
                  <?php
                }
                ?>
            </div>
          </div>
      </div>
    </div>
</div>
<?php
}else{
  echo "404 - FILM NIET GEVONDEN";
}
