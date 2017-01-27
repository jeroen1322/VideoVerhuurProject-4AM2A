<?php
if(!empty($_SESSION['login'])){
    $klantId = $_SESSION['login'][0];
    $klantNaam = $_SESSION['login'][1];
    $klantRolId = $_SESSION['login'][2];
    function isBalieMedewerker($klantRolId){
        if($klantRolId === 3){
            return true;
        }else{
            return false;
        }
    }
    if(isBalieMedewerker($klantRolId)) {
        ?>
        <div class="panel panel-default">
        <div class="panel-body">
        <div class="calendarophaaldata"> <?php
            $huidigeWeek = date('d-m-Y');
            echo $huidigeWeek;

            ?>
        </div>
        <div>
        <?php
        $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE besteld = 1 AND (afleverdatum=? OR ophaaldatum=?) AND afhandeling = 1");
        $stmt->bind_param('ss', $huidigeWeek, $huidigeWeek);
        $stmt->execute();

        $stmt->bind_result($order_id);
        $orderIdResult = array();
        while ($stmt->fetch()) {
            $orderIdResult[] = $order_id;
        }
        $stmt->close();
        if (!empty($orderIdResult)) {
            ?>
            <table class="table">
                <thead>
                <tr>
                    <th> Bezorgen of ophalen</th>
                    <th> Bezorgtijd</th>
                    <th> Naam</th>
                    <th> Adres</th>
                    <th> Woonplaats</th>
                    <th> Filmtitels</th>
                </tr>
                </thead>
            <tbody>
            <?php
            foreach($orderIdResult as $i){
                $stmt = DB::conn()->prepare("SELECT p.naam, p.adres, p.woonplaats FROM Persoon p, `Order` o where p.id = o.klantid and o.id=?");
                $stmt->bind_param('i', $i);
                $stmt->execute();
                $stmt->bind_result($naam, $adres, $woonplaats);
                $stmt->fetch();
                $stmt->close();
                ?>
                <tr>
                    <td></td>
                    <td><?php echo $naam ?></td>
                    <td><?php echo $adres ?></td>
                    <td><?php echo $woonplaats ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
            }
        } else{
            echo "geen data";                            }
                      ?>
                </div>
            </div>
        </div>
        <?php
    }
} ?>