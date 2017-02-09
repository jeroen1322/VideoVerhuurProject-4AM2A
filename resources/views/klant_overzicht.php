<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  $klantRolId = $_SESSION['login'][2];
  function isKlant($klantRolId){
    if($klantRolId === 1 || $klantRolId === 5){
      return true;
    }else{
      return false;
    }
  }
  function isGeblokkeerd($klantRolId){
    if($klantRolId === 5){
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
      <?php
      if(isGeblokkeerd($klantRolId)){
        echo "<div class='blocked'><b>UW ACCOUNT IS GEBLOKKEERD</b></div>";
      }
      ?>
      <h1>OVERZICHT</h1>
      <h3><b><?php echo $naam ?></b></h3>
      <hr></hr>
      <div class="left">
        <h4><b>INFORMATIE</b></h4>
        <?php
        if(!empty($_GET)){
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
            header("Refresh:0; url=/klant/overzicht");
          }
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
            <?php
            if(isGeblokkeerd($klantRolId)){
              ?>
              <input type="submit" class="btn btn-success bestel" value="PAS INFORMATIE AAN" disabled>
              <?php
            }else{
              ?>
              <input type="submit" class="btn btn-success bestel" value="PAS INFORMATIE AAN">
              <?php
            }
            ?>
          </form>
        </div>
        <?php
      }

      ?>
    </div>
    <div class="klant_right">
      <h4><b>ORDERS</b></h4>
      <?php
      //Haal id op van Order op
      $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=? AND besteld=1");
      $stmt->bind_param("i", $id);
      $stmt->execute();

      $stmt->bind_result($order_id);

      $orderIdResult = array();

      while($stmt->fetch()){
        $orderIdResult[] = $order_id;
      }

      $stmt->close();
      $u_orderIdResult = array_unique($orderIdResult);
      if(!empty($u_orderIdResult)){

        foreach($u_orderIdResult as $i){

          $stmt = DB::conn()->prepare("SELECT afleverdatum, ophaaldatum FROM `Order` WHERE id=?");
          $stmt->bind_param('i', $i);
          $stmt->execute();
          $stmt->bind_result($afleverdatum, $ophaaldatum);
          $stmt->fetch();
          $stmt->close();

          $stmt = DB::conn()->prepare("SELECT exemplaarid FROM Orderregel WHERE orderid=?");
          $stmt->bind_param('i', $i);
          $stmt->execute();
          $stmt->bind_result($exmid);
          $exemplaren = array();
          while($stmt->fetch()){
            $exemplaren[] =$exmid;
          }
          $stmt->close();

          $films = array();
          foreach($exemplaren as $e){
            $stmt = DB::conn()->prepare("SELECT filmid FROM Exemplaar WHERE id=?");
            $stmt->bind_param('i', $e);
            $stmt->execute();
            $stmt->bind_result($filmid);
            while($stmt->fetch()){
              $films[] = $filmid;
            }
            $stmt->close();
          }

          ?>
          <div class="order" data-toggle="collapse" data-target="#<?php echo $i ?>">
            <p class="order_info">#<?php echo $i ?><i class="fa fa-arrow-down neer" aria-hidden="true"></i></p>
            <div id="<?php echo $i ?>" class="collapse order_collapse">
              <h3>FILMS</h3>
              <table class="table">
                <thead>
                  <th><b>FOTO</b></th>
                  <th><b>TITEL</b></th>
                </thead>
                <tbody>
              <?php
              foreach($films as $f){
                $stmt = DB::conn()->prepare("SELECT titel, img FROM Film WHERE id=?");
                $stmt->bind_param('i', $f);
                $stmt->execute();
                $stmt->bind_result($titel, $img);
                $stmt->fetch();
                $stmt->close();

                $cover = "/cover/" . $img;
                $URL = "/film/" . $f;
                $titel = strtoupper($titel);
                $titel = str_replace('_', ' ', $titel);

                ?>
                <tr>
                  <td><a href="<?php echo $url ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
                  <td><?php echo $titel ?></td>
                </tr>
                <?php
              }
              ?>
              </tbody>
            </table>
            <h4><b>AFLEVERDATUM:</b> <?php echo $afleverdatum ?></h4>
            <h4><b>OPHAALDATUM:</b> <?php echo $ophaaldatum ?></h4>

            </div>
          </div>
          <?php

          // //Haal exemplaarid van Orderregel dat bij de Order hoort op
          // $or_stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
          // $or_stmt->bind_param("i", $i);
          // $or_stmt->execute();
          //
          // $or_stmt->bind_result($OR_id);
          // $exm_id = array();
          // while($or_stmt->fetch()){
          //   $exm_id[] = $OR_id;
          // }
          // $or_stmt->close();
          // // print_r($exm_id);
          //
          // //Haal de Filmid op van het exemplaar op
          // $exm_stmt = DB::conn()->prepare("SELECT filmid FROM `Exemplaar` WHERE id=?");
          // $exm_stmt->bind_param("i", $OR_id);
          // $exm_stmt->execute();
          //
          // $exm_stmt->bind_result($exm_film_id);
          // $exm_stmt->fetch();
          // $exm_stmt->close();
          //
          // //Haal alles van de film op dat overeen komt met de filmid van het exemplaar
          // $exm_film_stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` WHERE id=?");
          // $exm_film_stmt->bind_param("i", $exm_film_id);
          // $exm_film_stmt->execute();
          //
          // $exm_film_stmt->bind_result($film_id, $titel, $acteur, $omschr, $genre, $img);
          // $exm_film_stmt->fetch();
          // $exm_film_stmt->close();
          //
          //
          // if(!empty($film_id)){
          //   $cover = "/cover/" . $img;
          //   $URL = "/film/" . $film_id;
          //   $titel = strtoupper($titel);
          //   $titel = str_replace('_', ' ', $titel);
          //   // $bedrag = $bedrag / 100;
          // }
        }
      }else{
        echo "<div class='warning'><b>U HEEFT NOG GEEN FILMS BESTELD</b></div>";
      }
      ?>
    </div>
  </div>
</div>

  <?php
  }
DB::conn()->close();
}else{
  header("Refresh:0; url=/login");
}
