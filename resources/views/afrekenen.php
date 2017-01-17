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
  if(isKlant($klantRolId)){
    $stmt = DB::conn()->prepare("SELECT id, naam, adres, postcode, woonplaats, telefoonnummer, email FROM `Persoon` WHERE id=?");
    $stmt->bind_param('i', $klantId);
    $stmt->execute();
    $stmt->bind_result($id, $naam, $adres, $postcode, $woonplaats, $telefoonnummer, $email);
    $stmt->fetch();
    $stmt->close();

    //Haal id op van Order op
    $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=?");
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
          $bedrag
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
}
