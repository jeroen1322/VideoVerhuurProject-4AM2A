<div class="panel panel-default">
  <div class="panel-body">
    <h1>WINKELMAND</h1>

<?php
if(!empty($_SESSION['login'])){
  $klant = $_SESSION['login']['0'];

  //Haal id op van Order op
  $stmt = DB::conn()->prepare("SELECT id FROM `Order` WHERE klantid=?");
  $stmt->bind_param("i", $klant);
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
          <th>Prijs</th>
        </tr>
      </thead>
      <tbody>
    <?php
    $bedr_stmt = DB::conn()->prepare("SELECT bedrag FROM `Order` WHERE klantid=?");
    $bedr_stmt->bind_param("i", $klant);
    $bedr_stmt->execute();
    $bedr_stmt->bind_result($bedrag);
    $bedr_stmt->fetch();
    $bedr_stmt->close();

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

      //HIER ZIT NOG EEN BUG!
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
            <td><?php echo $omschr ?></td>
            <td>€<?php echo $bedrag ?>0<td>
          </tr>
        <?php
      }
    }
    DB::conn()->close();
  }else{
    echo "<div class='warning'>UW WINKELMAND IS NOG LEEG</div>";
  }


}else{
  echo "U MOET <a href='/login'>INGELOGD</a> ZIJN";
}

?>
    </tbody>
  </table>
  <?php
  if(!empty($bedrag)){
    $totaal = $bedrag * count($orderIdResult);
    ?>
    <div class="winkelmand_onder">
      <h4 class="totaal"><b>TOTAAL: €<?php echo $totaal; ?></b></h4>
      <a href="/winkelmand">
        <button class="btn btn-success bestel">AFREKENEN</button>
      </a>
    </div>
    <?php
  }
  ?>
  </div>
</div>
