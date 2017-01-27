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
?>
<div class="panel panel-default">
    <div class="panel-body">
        <h1></h1>

        <?php
        //test
        //Pak de foto van de film
        $stmt = DB::conn()->prepare("SELECT id FROM `Order`");
        $stmt->execute();
        $stmt->bind_result($id);
        $order_id = array();
        while($stmt->fetch()){
            $order_id[] = $id;
        }
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

</div>
<?php
        $stmt->close();
        if(!empty($id)){
            foreach($order_id as $i){
                $stmt = DB::conn()->prepare("SELECT o.id, p.naam, p.adres, p.woonplaats, o.aflevertijd, o.ophaaltijd, o.afleverdatum, o.ophaaldatum FROM Persoon p, `Order` o where afhandeling = 0 and besteld  = 1;");
                $stmt->execute();
                $stmt->bind_result($id, $naam, $adres, $woonplaats, $aflevertijd, $ophaaltijd, $afleverdatum, $ophaaldatum);

                $stmt->fetch();
                $stmt->close();
                //$url =  "/film/" . $titel;
                //$titel = str_replace('_', ' ', $titel);
                //$titel = strtoupper($titel);
                //$cover = "/cover/" . $img;

                ?>
    <tr>

        <td><?php echo $id ?></td>
        <td><?php echo $naam ?></td>
        <td><?php echo $woonplaats ?></td>

        <td><?php echo $ophaaldatum ?></td>
        <td><?php echo $ophaaltijd ?></td>
        <td><?php echo $afleverdatum ?></td>
        <td><?php echo $aflevertijd ?></td>
        <td></td></tr></table>

            <?php
            }
          }
          else {
              echo "geen data";
          }
        ?>
    </div>
</div>
<?php
  }
}else{
  header("Refresh:0; url=/login");
}
?>
