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

    $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=? AND besteld=1");
    $stmt->bind_param("i", $klantId);
    $stmt->execute();
    $stmt->bind_result($afgerond);
    $alAfgerond = array();
    while($stmt->fetch()){
      $alAfgerond[] = $afgerond;
    }
    $stmt->close();

    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h1>AFREKENEN</h1>
        <?php
        $huidigeTijd = date('H:i');

        if(!empty($_GET['action'])){
          if($_GET['action'] == 'ok'){
            $code = $_GET['code'];
            $action = $_GET['action'];
            $ophaalTijd = $_POST['ophaalTijd'];

            foreach($orderIdResult as $e){
              $exm_order_stmt = DB::conn()->prepare("UPDATE `Order` SET ophaaltijd=?, besteld=1 WHERE id=?");
              $exm_order_stmt->bind_param("si", $ophaalTijd, $e);
              $exm_order_stmt->execute();
              $exm_order_stmt->close();
            }
            ?>
            <h2><b>U HEEFT €<?php echo $bedrag ?> BETAALD</b></h2>
            <a href="/"><button class="btn btn-success bestel">TERUG NAAR HOME</button></a>
            <?php
          }elseif($_GET['action'] == 'afleverDatum'){
            ?>
            <h2>AFLEVER DATUM</h2>
            <form method="post" action="?action=afleverTijd">
              <select class="form-control" name="afleverDatum">
                <?php
                $ophaalDatum = date('d-m-Y');
                // $ophaalDatum = date('d-m-Y', strtotime($ophaalDatum."+1 day"));
                for($x=0; $x <= 14; $x++){
                  $date = date('d-m-Y', strtotime($ophaalDatum.'+'.$x. 'days'));
                  ?>
                  <option value="<?php echo $date ?>"><?php echo $date ?></option>
                  <?php
                }
                ?>
              </select>
              <input type="submit" class="btn btn-success bestel" value="SELECTEER AFLEVER TIJD">
            </form>
            <?php
          }elseif($_GET['action'] == 'afleverTijd'){
            $afleverDatum = $_POST['afleverDatum'];
            $stmt = DB::conn()->prepare("SELECT `aflevertijd` FROM `Order` WHERE afleverdatum=?");
            $stmt->bind_param('s', $afleverDatum);
            $stmt->execute();
            $bezetteAfleverTijd = array();
            $stmt->bind_result($f);
            while($stmt->fetch()){
              $bezetteAfleverTijd[] = $f;
            }
            $stmt->close();
            $afleverDate = $_POST['afleverDatum'];
            foreach($orderIdResult as $e){
              $exm_order_stmt = DB::conn()->prepare("UPDATE `Order` SET afleverdatum=? WHERE id=?");
              $exm_order_stmt->bind_param("si", $afleverDate, $e);
              $exm_order_stmt->execute();
              $exm_order_stmt->close();
            }
            ?>
            <h4>Aflever datum: <?php echo $_POST['afleverDatum'] ?></h4>
            <h2>AFLEVER TIJD</h2>
            <form method="post" action="?action=ophaalDatum">
              <select name="afleverTijd" class="form-control">
                <?php
                for($x=0; $x <= 120; $x=$x+10){
                  $afleverTime = strtotime('14:00');
                  $afleverTime = Date('H:i', strtotime("+".$x. " minutes", $afleverTime));
                  if(!in_array($afleverTime, $bezetteAfleverTijd)){
                    ?>
                    <option value="<?php echo $afleverTime ?>"><?php echo $afleverTime ?></option>
                    <?php
                  }
                }
                ?>
              </select>
              <input type="submit" class="btn btn-success bestel" value="OPHAAL DATUM">
              <input type="hidden" value="<?php echo $_POST['afleverDatum']; ?>" name="afleverDatum">
            </form>
            <?php
          }elseif($_GET['action'] == 'ophaalDatum'){
            $afleverTijd = $_POST['afleverTijd'];

            foreach($orderIdResult as $e){
              $exm_order_stmt = DB::conn()->prepare("UPDATE `Order` SET aflevertijd=? WHERE id=?");
              $exm_order_stmt->bind_param("si", $afleverTijd, $e);
              $exm_order_stmt->execute();
              $exm_order_stmt->close();
            }
            ?>
            <h4>Aflever datum: <?php echo $_POST['afleverDatum'] ?></h4>
            <h4>Aflever Tijd: <?php echo $_POST['afleverTijd'] ?></h4>
            <h2>OPHAAL DATUM</h2>
            <form method="post" action="?action=ophaalTijd">
              <select class="form-control" name="ophaalDatum">
                <?php
                $ophaalDatum = date('d-m-Y');
                $ophaalDatum = date('d-m-Y', strtotime($ophaalDatum."+1 day"));
                for($x=0; $x <= 14; $x++){
                  $date = date('d-m-Y', strtotime($ophaalDatum.'+'.$x. 'days'));
                  ?>
                  <option value="<?php echo $date ?>"><?php echo $date ?></option>
                  <?php
                }
                ?>
              </select>
              <input type="submit" class="btn btn-success bestel" value="SELECTEER OPHAAL TIJD">
              <input type="hidden" value="<?php echo $_POST['afleverTijd']?>" name="afleverTijd">
              <input type="hidden" value="<?php echo $_POST['afleverDatum']?>" name="afleverDatum">
            </form>
            <?php
          }elseif($_GET['action'] == 'ophaalTijd'){
            $ophaalDatum = $_POST['ophaalDatum'];
            $stmt = DB::conn()->prepare("SELECT `ophaaltijd` FROM `Order` WHERE ophaaldatum=?");
            $stmt->bind_param('s', $ophaalDatum);
            $stmt->execute();
            $bezetteOphaalTijd = array();
            $stmt->bind_result($f);
            while($stmt->fetch()){
              $bezetteOphaalTijd[] = $f;
            }
            $stmt->close();

            foreach($orderIdResult as $e){
              $exm_order_stmt = DB::conn()->prepare("UPDATE `Order` SET ophaaldatum=? WHERE id=?");
              $exm_order_stmt->bind_param("si", $ophaalDatum, $e);
              $exm_order_stmt->execute();
              $exm_order_stmt->close();
            }
            //Bereken het aantal dagen tussen de aflever en ophaal datum
            $dateBegin = $_POST['afleverDatum'];
            $afleverDatumCalc = strtotime($dateBegin);
            $dateEinde = $_POST['ophaalDatum'];
            $ophaalDatumCalc = strtotime($dateEinde);
            $diff = $ophaalDatumCalc - $afleverDatumCalc;
            $days = floor($diff / (60*60*24) ); //Seconden naar dagen omrekenen

            ?>
            <h4>Aflever datum: <?php echo $_POST['afleverDatum'] ?></h4>
            <h4>Aflever Tijd: <?php echo $_POST['afleverTijd'] ?></h4><hr></hr>
            <h4>Huur periode :
              <?php
              if($days == 1){
                echo $days . " dag";
              }else{
                echo $days . " dagen";
              }
              ?>
            </h4>
            <hr></hr>
            <h4>Ophaal Datum: <?php echo $_POST['ophaalDatum'] ?></h4>
            <h2>OPHAAL TIJD</h2>
            <form method="post" action="?action=ok">
              <select name="ophaalTijd" class="form-control">
                <?php
                // $beginTijd = '14:00';
                for($x=0; $x <= 120; $x=$x+10){
                  $ophaalTime = strtotime('14:00');
                  $ophaalTime = Date('H:i', strtotime("+".$x. " minutes", $ophaalTime));
                  if(!in_array($ophaalTime, $bezetteOphaalTijd)){
                    ?>
                    <option value="<?php echo $ophaalTime ?>"><?php echo $ophaalTime ?></option>
                    <?php
                  }
                }
                ?>
              </select>
              <input type="submit" class="btn btn-success bestel" value="AFRONDEN">
            </form>
            <?php
          }
        }else{

        if(!empty($orderIdResult)){

          // $stmt = DB::conn()->prepare("SELECT `aflevertijd` FROM `Order`");
          // $stmt->execute();
          // $bezetteAfleverTijden = array();
          // $stmt->bind_result($f);
          // while($stmt->fetch()){
          //   $bezetteAfleverTijden[] = $f;
          // }
          // $stmt->close();


          ?>
          <br>
          <form  method="post" action="?action=afleverDatum">
          <h4>CONTROLLEER UW GEGEVENS</h4>
          <div class="links">
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

                <input type="submit" class="btn btn-success bestel" value="DE GEGEVENS KLOPPEN">
              </form>
            </ul>
          </div>
          <div class="right">
          </div>
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
