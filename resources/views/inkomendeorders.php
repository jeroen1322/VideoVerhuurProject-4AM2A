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
            if($action == 'afgehandeld') {
                $afhandeling = 1;

                $stmt = DB::conn()->prepare("UPDATE `Order` SET afhandeling=? WHERE id=?;");
                $stmt->bind_param("ii", $afhandeling, $code);
                $stmt->execute();
                $stmt->close();
            }

        }
        ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="btn-group admin">
                    <a href="/baliemedewerker/inkomendeorders" class="btn btn-primary actief admin_menu">BINNENGEKOMEN ORDERS</a>
                    <a href="/baliemedewerker/bezorgdata" class="btn btn-primary admin_menu">BEZORGDATA</a>
                    <a href="/eigenaar/film_aanpassen" class="btn btn-primary admin_menu">FILM INFO BEHEREN</a>
                </div>
                <h1> Binnengekomen orders</h1>

                <?php
                $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE afhandeling=0");
                $stmt->execute();
                $stmt->bind_result($id);
                $order_id = array();
                while($stmt->fetch()){
                    $order_id[] = $id;
                }
                $stmt->close();
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
        if(!empty($id)){
            foreach($order_id as $i){

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
                    <td><?php echo $ophaaldatum ?></td>
                    <td><?php echo $ophaaltijd ?></td>
                    <td><?php echo $afleverdatum ?></td>
                    <td><?php echo $aflevertijd ?></td>
                    <td></td>
                    <td>
                      <form method="post" action="?action=afgehandeld&code=<?php echo $i ?>">
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
          echo "GEEN FILMS";
        }
      }
    }
?>
