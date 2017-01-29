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
    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="btn-group admin">
          <a href="/eigenaar/overzicht" class="btn btn-primary admin_menu">OVERZICHT</a>
          <a href="/eigenaar/film_toevoegen" class="btn btn-primary admin_menu">FILM TOEVOEGEN</a>
          <a href="/eigenaar/film_verwijderen" class="btn btn-primary actief admin_menu">FILM VERWIJDEREN</a>
          <a href="/eigenaar/film_aanpassen" class="btn btn-primary admin_menu">FILM INFO BEHEREN</a>
          <a href="/eigenaar/klant_blokkeren" class="btn btn-primary admin_menu">KLANT BLOKKEREN</a>
        </div>
    <h1>FILM VERWIJDEREN</h1>
    <?php
    $stmt = DB::conn()->prepare("SELECT id FROM `Film`");
    $stmt->execute();
    $stmt->bind_result($id);
    $film_id = array();
    while($stmt->fetch()){
      $film_id[] = $id;
    }
    $stmt->close();
    if(!empty($id)){
      ?>
      <table class="table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Titel</th>
            <th>Omschrijving</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
      <?php

      foreach($film_id as $i){
        $stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` WHERE id=?");
        $stmt->bind_param("i", $i);
        $stmt->execute();
        $stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
        $stmt->fetch();
        $stmt->close();
        $cover = "/cover/" . $img;
        $URL = "/film/" . $id;
        ?>
        <tr>
          <td><a href="<?php echo $URL ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
          <td><?php echo $titel ?></td>
          <td><?php echo $omschr ?><td>
          <td>
            <form method="post" action="?action=delete&code=<?php echo $id ?>">
              <button type="submit" class="btn btn-success">
                  <i class="fa fa-trash-o" aria-hidden="true"></i>
              </button>
            </form>
          </td>
        </tr>
        <?php
      }
      if(!empty($_GET['action'])){
        if($_GET['action'] == 'delete'){
          $code = $_GET['code'];

          $exm_film_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=?");
          $exm_film_stmt->bind_param("i", $code);
          $exm_film_stmt->execute();

          $exm_film_stmt->bind_result($x);
          $exm_film_stmt->fetch();
          $exm_film_stmt->close();

          $exm_order_stmt = DB::conn()->prepare("SELECT orderid FROM `Orderregel` WHERE exemplaarid=?");
          $exm_order_stmt->bind_param("i", $x);
          $exm_order_stmt->execute();
          $exm_order_stmt->bind_result($OR_order_id);
          $exm_order_stmt->fetch();
          $exm_order_stmt->close();

          $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Order` WHERE id=?");
          $exm_order_stmt->bind_param("i", $OR_order_id);
          $exm_order_stmt->execute();
          $exm_order_stmt->close();

          $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Orderregel` WHERE exemplaarid=?");
          $exm_order_stmt->bind_param("i", $x);
          $exm_order_stmt->execute();
          $exm_order_stmt->close();

          $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Exemplaar` WHERE filmid=?");
          $exm_order_stmt->bind_param("i", $code);
          $exm_order_stmt->execute();
          $exm_order_stmt->close();

          $exm_film_stmt = DB::conn()->prepare("SELECT img FROM `Film` WHERE id=?");
          $exm_film_stmt->bind_param("i", $code);
          $exm_film_stmt->execute();

          $exm_film_stmt->bind_result($verwijderFoto);
          $exm_film_stmt->fetch();
          $exm_film_stmt->close();

          unlink(FOTO . "/" . $verwijderFoto);

          $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Film` WHERE id=?");
          $exm_order_stmt->bind_param("i", $code);
          $exm_order_stmt->execute();
          $exm_order_stmt->close();


          header("Refresh:0; url=/eigenaar/film_verwijderen");
        }
      }
      DB::conn()->close();
    }else{
      echo "<div class='warning'><b>ER ZIJN GEEN FILMS IN DE DATABASE</b></div>";
    }
  }else{
    echo "NOPE HIER MAG JE NIET KOMEN!";
  }
}else{
  header("Refresh:0; url=/login");
}
