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

    if(!empty($_GET['action'])){
      $code = $_GET['code'];
      $action = $_GET['action'];
      $edit = true;
    }else{
      $edit = false;
    }

  ?>
  <div class="panel panel-default">
    <div class="panel-body">
      <h1>OVERZICHT</h1>
      <h3><b><?php echo $naam ?></b></h3>
      <hr></hr>

      <?php
      if($edit == true && $code == $id && $action == 'edit'){
        ?>
        <div class="info">
          <form method="post" action=?action=save&code=<?php echo $id ?>>

          <p><b>ALGEMENE INFORMATIE</b></p>
          <ul class="list-group">
            <li class="list-group-item"><b>Klantnummer: </b><?php echo $id ?></li>
            <li class="list-group-item"><b>Naam: </b><input type="text" class="form-control" name="naam" value="<?php echo $naam ?>"></li>
          </ul>

          <p><b>CONTACT INFORMATIE</b></p>
          <ul class="list-group">
            <li class="list-group-item"><b>Email: </b><input type="email" class="form-control" name="email" value="<?php echo $email ?>"></li>
            <li class="list-group-item"><b>Telefoonnummer: </b><input type="text" class="form-control" name="telefoonnummer" value="<?php echo $telefoonnummer ?>"></li>
          </ul>

          <p><b>ADRES INFORMATIE</b></p>
          <ul class="list-group">
            <li class="list-group-item"><b>Adres: </b><input type="text" class="form-control" name="adres" value="<?php echo $adres ?>"></li>
            <li class="list-group-item"><b>Postcode: </b><input type="text" class="form-control" name="postcode" value="<?php echo $postcode ?>"></li>
            <li class="list-group-item"><b>Woonplaats: </b><input type="text" class="form-control" name="woonplaats" value="<?php echo $woonplaats ?>"></li>
          </ul>
          <form method="post" action="?action=edit&code=<?php echo $id ?>">
            <button type="submit" class="btn btn-success bestel"><li class="fa fa-floppy-o"></li> OPSLAAN</button>
          </form>
        </div>
        <?php
      }elseif($action == 'save'){
        $stmt = DB::conn()->prepare("UPDATE `Persoon` SET `naam`=?, `email`=?, `telefoonnummer`=?, `adres`=?, `postcode`=?, `woonplaats`=? WHERE id=?");
        $stmt->bind_param("ssssssi", $_POST['naam'], $_POST['email'], $_POST['telefoonnummer'], $_POST['adres'], $_POST['postcode'], $_POST['woonplaats'], $code);
        $stmt->execute();
        $stmt->close();
        header("Refresh:0; url=/klant/overzicht");
      }else{
      ?>
      <div class="info">
        <p><b>ALGEMENE INFORMATIE</b></p>
        <ul class="list-group">
          <li class="list-group-item"><b>Klantnummer: </b><?php echo $id ?></li>
          <li class="list-group-item"><b>Naam: </b><?php echo $naam?></li>
          <li class="list-group-item"><b></b></li>
        </ul>

        <p><b>CONTACT INFORMATIE</b></p>
        <ul class="list-group">
          <li class="list-group-item"><b>Email: </b><?php echo $email ?></li>
          <li class="list-group-item"><b>Telefoonnummer: </b><?php echo $telefoonnummer ?></li>
        </ul>

        <p><b>ADRES INFORMATIE</b></p>
        <ul class="list-group">
          <li class="list-group-item"><b>Adres: </b><?php echo $adres ?></li>
          <li class="list-group-item"><b>Postcode: </b><?php echo $postcode ?></li>
          <li class="list-group-item"><b>Woonplaats: </b><?php echo $woonplaats ?></li>
        </ul>
        <form method="post" action="?action=edit&code=<?php echo $id ?>">
          <input type="submit" class="btn btn-success bestel" value="PAS INFORMATIE AAN">
        </form>
      </div>
      <?php
    }

      ?>
    </div>
  </div>

  <?php
  }
DB::conn()->close();
}
