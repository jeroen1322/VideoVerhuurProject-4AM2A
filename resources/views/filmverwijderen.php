<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  function isEigenaar($klantId){
    if($klantId === 1){
      return true;
    }else{
      return false;
    }
  }
  if(isEigenaar($klantId)){
    ?>
<div class="panel panel-default">
  <div class="panel-body">
    <h1>VIDEO VERWIJDEREN</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Titel</th>
          <th>Omschr</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
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
      foreach($film_id as $i){
        $stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` WHERE id=?");
        $stmt->bind_param("i", $i);
        $stmt->execute();
        $stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
        $stmt->fetch();
        $stmt->close();
        $cover = "/cover/" . $img;
        $URL = "/film/" . $titel;
        ?>
        <tr>
          <td><a href="<?php echo $URL ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
          <td><?php echo $titel ?></td>
          <td><?php echo $omschr ?><td>
          <td>
            <form method="post" action="?action=delete&code=<?php echo $id ?>">
              <button type="submit" class="btn btn-success">
                  <i class="fa fa-times-circle-o" aria-hidden="true"></i>
              </button>
            </form>
          </td>
        </tr>
        <?php
      }
      if(!empty($_GET['action'])){

        $code = $_GET['code'];

        $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Exemplaar` WHERE filmid=?");
        $exm_order_stmt->bind_param("i", $code);
        $exm_order_stmt->execute();
        $exm_order_stmt->close();
        
        $exm_order_stmt = DB::conn()->prepare("DELETE FROM `Film` WHERE id=?");
        $exm_order_stmt->bind_param("i", $code);
        $exm_order_stmt->execute();
        $exm_order_stmt->close();
        header("Refresh:0; url=/eigenaar/film_verwijderen");
      }
      DB::conn()->close();
    }else{
      echo "<div class='warning'><b>ER ZIJN NOG GEEN FILMS TOEGEVOEGD</b></div>";
    }
  }else{
    echo "NOPE HIER MAG JE NIET KOMEN!";
  }
}else{
  echo "405 - GEEN TOEGANG";
}
