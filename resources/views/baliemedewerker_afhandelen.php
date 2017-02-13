<?php
if(!empty($_SESSION['login'])){
    $klantId = $_SESSION['login'][0];
    $klantNaam = $_SESSION['login'][1];
    $klantRolId = $_SESSION['login'][2];
    function isEigenaar($klantRolId){
        if($klantRolId === 3){
            return true;
        }else{
            return false;
        }
    }
    if(isEigenaar($klantRolId)){
        if(!empty($_GET)){
            $code = $_GET['code'];
            $action = $_GET['action'];
            $exm = $_GET['exm'];
            if($action == 'afgehandeld') {
                $afhandeling = 1;

                $stmt = DB::conn()->prepare("UPDATE `Order` SET afhandeling=1, openbedrag=0, besteld=1 WHERE id=?;");
                $stmt->bind_param("i", $code);
                $stmt->execute();
                $stmt->close();

                $stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
                $stmt->bind_param('i', $code);
                $stmt->execute();
                $stmt->bind_result($e);
                while($stmt->fetch()){
                  $exms[] = $e;
                }
                $stmt->close();

                foreach($exms as $ex){
                  $stmt = DB::conn()->prepare("UPDATE `Exemplaar` SET statusid=1 WHERE id=?;");
                  $stmt->bind_param("i", $ex);
                  $stmt->execute();
                  $stmt->close();
                }

            }

        }
        ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="btn-group admin">
                    <a href="/baliemedewerker/afhandelen" class="btn btn-primary actief admin_menu">Afhandelen</a>
                    <a href="/baliemedewerker/bezorgdata" class="btn btn-primary admin_menu">BEZORGDATA</a>
                    <a href="/baliemedewerker/extraopties" class="btn btn-primary admin_menu">EXTRA OPTIES</a>
                </div>
                <h1>Afhandelen</h1>

                <?php
                $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE afhandeling=0 AND besteld=1");
                $stmt->execute();
                $stmt->bind_result($id);
                $order_id = array();
                while($stmt->fetch()){
                    $order_id[] = $id;
                }
                $stmt->close();

        if(!empty($id)){
          ?>
          <div>
          <table class="table">
              <thead>
              <tr>
                  <th>Id</th>
                  <th>Naam</th>
                  <th>Woonplaats</th>
                  <th>Datum wegbrengen</th>
                  <th>Tijd</th>
                  <th>Datum ophalen</th>
                  <th>Tijd</th>
                  <th>Titels</th>
                  <th>Afhandeling</th>
              </tr>
              </thead>
              <tbody>
          </div>
          <?php
            foreach($order_id as $i){
              $stmt = DB::conn()->prepare("SELECT exemplaarid FROM `Orderregel` WHERE orderid=?");
              $stmt->bind_param('i', $i);
              $stmt->execute();
              $stmt->bind_result($exmid);
              $stmt->fetch();
              $stmt->close();

              $stmt = DB::conn()->prepare("SELECT o.id, p.naam, p.adres, p.woonplaats, o.aflevertijd, o.ophaaltijd, o.afleverdatum, o.ophaaldatum FROM Persoon p, `Order` o where afhandeling = 0 and besteld  = 1 and o.id=?;");
              $stmt->bind_param("i", $i);
              $stmt->execute();
              $stmt->bind_result($id, $naam, $adres, $woonplaats, $aflevertijd, $ophaaltijd, $afleverdatum, $ophaaldatum);
              $stmt->fetch();
              $stmt->close();
              ?>
              <tr>
                <td><?php echo $id ?></td>
                <td><?php echo $naam ?></td>
                <td><?php echo $woonplaats ?></td>
                <td><?php echo $afleverdatum ?></td>
                <td><?php echo $aflevertijd ?></td>
                <td><?php echo $ophaaldatum ?></td>
                <td><?php echo $ophaaltijd ?></td>
                <td></td>
                <td>
                  <form method="post" action="?action=afgehandeld&code=<?php echo $i ?>&exm=<?php echo $exmid?>">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check unblock"></i>
                        </button>
                    </form>
                  </td>
                </tr>
                <?php
          }
          ?>
          </table>
          <?php
        }else{
          // header("Refresh:0; url=/login");
          echo "<div class='warning'><b>ER ZIJN GEEN OPEN BESTELLINGEN</b></div>";
        }
      }
    }
?>
            </div>
