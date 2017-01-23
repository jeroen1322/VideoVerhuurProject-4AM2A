<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  $klantRolId = $_SESSION['login'][2];
  function isKlant($klantRolId){
    if($klantRolId === 1){
      return true;
    }else{
      return false;
    }
  }
  function isEigenaar($klantRolId){
    if($klantRolId === 4){
      return true;
    }else{
      return false;
    }
  }
  if(isKlant($klantRolId) || isEigenaar($klantRolId)){
    $stmt = DB::conn()->prepare("SELECT id, naam, adres, postcode, woonplaats, telefoonnummer, email FROM `Persoon` WHERE id=?");
    $stmt->bind_param('i', $klantId);
    $stmt->execute();
    $stmt->bind_result($id, $naam, $adres, $postcode, $woonplaats, $telefoonnummer, $email);
    $stmt->fetch();
    $stmt->close();

    //Haal id op van Order op
    $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=? AND besteld=0");
    $stmt->bind_param("i", $klantId);
    $stmt->execute();

    $stmt->bind_result($order_id);

    $orderIdResult = array();

    while($stmt->fetch()){
      $orderIdResult[] = $order_id;
    }

    $stmt->close();
    $bedrag = count($orderIdResult) * 7.50;


    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h1>AFREKENEN</h1>
        <?php
        if(!empty($_GET['action'])){
          $code = $_GET['code'];
          $action = $_GET['action'];

          foreach($orderIdResult as $e){
            $exm_order_stmt = DB::conn()->prepare("UPDATE `Order` SET besteld=1 WHERE id=?");
            $exm_order_stmt->bind_param("i", $e);
            $exm_order_stmt->execute();
            $exm_order_stmt->close();
          }
          ?>
          <h2><b>U HEEFT €<?php echo $bedrag ?> BETAALD</b></h2>
          <a href="/"><button class="btn btn-success bestel">TERUG NAAR HOME</button></a>
          <?php
        }else{

        if(!empty($orderIdResult)){
          ?>
          <br>
          <h4>CONTROLLEER UW GEGEVENS</h4>
          <ul class="list-group">
            <li class="list-group-item"><b>Naam: </b><?php echo $naam?></li>
            <li class="list-group-item"><b>Email: </b><?php echo $email ?></li>
            <li class="list-group-item"><b>Telefoonnummer: </b><?php echo $telefoonnummer ?></li>
            <li class="list-group-item"><b>Adres: </b><?php echo $adres ?></li>
            <li class="list-group-item"><b>Postcode: </b><?php echo $postcode ?></li>
            <li class="list-group-item"><b>Woonplaats: </b><?php echo $woonplaats ?></li>
            <?php
            foreach($orderIdResult as $e){
              $stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
              $stmt->bind_param("i", $e);
              $stmt->execute();
              $stmt->bind_result($exm_id);
              $stmt->fetch();
              $stmt->close();

              $stmt = DB::conn()->prepare("SELECT filmid FROM `Exemplaar` WHERE id=?");
              $stmt->bind_param("i", $exm_id);
              $stmt->execute();
              $stmt->bind_result($filmid);
              $stmt->fetch();
              $stmt->close();

              $stmt = DB::conn()->prepare("SELECT id, titel, img FROM `Film` WHERE id=?");
              $stmt->bind_param("i", $filmid);
              $stmt->execute();
              $stmt->bind_result($film_id, $titel, $img);
              $stmt->fetch();
              $stmt->close();

              // echo $film_id . "<br>" . $titel . "<br>" . $img . "<br><br>";
            }
             ?>
            <form method="post" action="?action=ok&code=<?php echo $id ?>">
              <input type="submit" class="btn btn-success bestel" value="DE GEGEVENS KLOPPEN">
            </form>
          </ul>
          <?php
        }
      }
    }
  ?>
</div>
</div>
<?php
  DB::conn()->close();
}else{
  header("Refresh:0; url=/login");
}
