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
                $stmt = DB::conn()->prepare("select o.id, d.datum, d.tijd, p.naam, p.woonplaats, f.titel, o.afhandeling
                                                from `Order` o, Datum d, Persoon p, Orderregel orgl, Exemplaar e, Film f
                                                where o.besteld = true
                                                and o.afhandeling = false
                                                AND d.ordernr = o.id
                                                AND d.datum >= sysdate() 
                                                AND p.id = o.klantid
                                                AND orgl.orderid = o.id
                                                AND e.id = orgl.exemplaarid
                                                AND f.id = e.filmid
                                                ");
                $stmt->bind_param("i", $i);
                $stmt->execute();
                $stmt->bind_result($id, $datum, $tijd, $naam, $woonplaats, $titel, $afhandeling);

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

        <td><?php echo $datum ?></td>
        <td><?php echo $tijd ?></td>
        <td><?php echo $datum ?></td>
        <td><?php echo $tijd ?></td>
        <td><?php echo $titel ?></td></tr></table>

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
