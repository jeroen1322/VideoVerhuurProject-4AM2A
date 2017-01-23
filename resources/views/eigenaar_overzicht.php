<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  $klantRolId = $_SESSION['login'][2];
  function isEigenaar($klantRolId){
    if($klantRolId === 4){
      return true;
    }else{
      return false;
    }
  }
  if(isEigenaar($klantRolId)){
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
        <div class="btn-group admin">
          <a href="/eigenaar/overzicht" class="btn btn-primary actief admin_menu">OVERZICHT</a>
          <a href="/eigenaar/film_toevoegen" class="btn btn-primary admin_menu">FILM TOEVOEGEN</a>
          <a href="/eigenaar/film_verwijderen" class="btn btn-primary admin_menu">FILM VERWIJDEREN</a>
          <a href="/eigenaar/film_aanpassen" class="btn btn-primary admin_menu">FILM INFO BEHEREN</a>
          <a href="/eigenaar/klant_blokkeren" class="btn btn-primary admin_menu">KLANT BLOKKEREN</a>
        </div>
        <h1>EIGENAAR OVERZICHT</h1>
        <div class="left">
          <?php
          if($edit == true && $code == $id && $action == 'edit'){
            ?>
            <div class="info">
              <form method="post" action=?action=save&code=<?php echo $id ?>>

              <h5>ALGEMENE INFORMATIE</h5>
              <ul class="list-group">
                <li class="list-group-item"><b>Klantnummer: </b><?php echo $id ?></li>
                <li class="list-group-item"><b>Naam: </b><input type="text" class="form-control" name="naam" value="<?php echo $naam ?>" required></li>
              </ul>

              <h5>CONTACT INFORMATIE</h5>
              <ul class="list-group">
                <li class="list-group-item"><b>Email: </b><input type="email" class="form-control" name="email" value="<?php echo $email ?>" required></li>
                <li class="list-group-item"><b>Telefoonnummer: </b><input type="text" class="form-control" name="telefoonnummer" value="<?php echo $telefoonnummer ?>" required></li>
              </ul>

              <h5>ADRES INFORMATIE</h5>
              <ul class="list-group">
                <li class="list-group-item"><b>Adres: </b><input type="text" class="form-control" name="adres" value="<?php echo $adres ?>" required></li>
                <li class="list-group-item"><b>Postcode: </b><input type="text" class="form-control" name="postcode" value="<?php echo $postcode ?>" required></li>
                <li class="list-group-item"><b>Woonplaats: </b><input type="text" class="form-control" name="woonplaats" value="<?php echo $woonplaats ?>" required></li>
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
            header("Refresh:0; url=/eigenaar/overzicht");
          }else{
          ?>
          <div class="info">
            <h5>ALGEMENE INFORMATIE</h5>
            <ul class="list-group">
              <li class="list-group-item"><b>Klantnummer: </b><?php echo $id ?></li>
              <li class="list-group-item"><b>Naam: </b><?php echo $naam?></li>
              <li class="list-group-item"><b></b></li>
            </ul>

            <h5>CONTACT INFORMATIE</h5>
            <ul class="list-group">
              <li class="list-group-item"><b>Email: </b><?php echo $email ?></li>
              <li class="list-group-item"><b>Telefoonnummer: </b><?php echo $telefoonnummer ?></li>
            </ul>

            <h5>ADRES INFORMATIE</h5>
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
        <div class="klant_right">
          <h4>ORDERS</h4>
          <?php
          //Haal id op van Order op
          $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE besteld=1");
          $stmt->bind_param("i", $id);
          $stmt->execute();

          $stmt->bind_result($order_id);

          $orderIdResult = array();

          while($stmt->fetch()){
            $orderIdResult[] = $order_id;
          }

          $stmt->close();
          if(!empty($orderIdResult)){
            ?>
            <table class="table">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Titel</th>
                  <th>Omschrijving</th>
                </tr>
              </thead>
              <tbody>
            <?php
            foreach($orderIdResult as $i){
              //Haal exemplaarid van Orderregel dat bij de Order hoort op
              $or_stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
              $or_stmt->bind_param("i", $i);
              $or_stmt->execute();

              $or_stmt->bind_result($OR_id);
              $exm_id = array();
              while($or_stmt->fetch()){
                $exm_id[] = $OR_id;
              }
              $or_stmt->close();
              // print_r($exm_id);

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


              if(!empty($film_id)){
                $cover = "/cover/" . $img;
                $URL = "/film/" . $titel;
                $titel = strtoupper($titel);
                $titel = str_replace('_', ' ', $titel);
                ?>
                  <tr>
                    <td><a href="<?php echo $URL ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
                    <td><?php echo $titel ?></td>
                    <td><?php echo $omschr ?><td>
                  </tr>
                <?php
              }
            }
          }else{
            echo "<div class='warning'><b>ER ZIJN NOG GEEN ORDERS GEPLAATS</b></div>";
          }
          ?>
      </div>
      </div>
    </div>
  <?php
  }else{
    echo "404";
  }
}else{
  header("Refresh:0; url=/login");
}
