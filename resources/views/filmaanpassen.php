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
    if(!empty($_GET['action'])){
      $code = $_GET['code'];
      $edit = true;
    }else{
      $edit = false;
    }

    ?>
<div class="panel panel-default">
  <div class="panel-body">
    <h1>FILM INFORMATIE BEHEREN</h1>
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
            <th>Omschr</th>
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
        $URL = "/film/" . $titel;

        if($edit == true && $code == $id){
          ?>
          <tr>
            <td><a href="<?php echo $URL ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
            <td>
              <form method="post" action="?action=delete&code=<?php echo $id ?>">
                <input type="text" value="<?php echo $titel ?>">
              </form>
            </td>
            <td><?php echo $omschr ?><td>
            <td>
              <form method="post" action="?action=delete&code=<?php echo $id ?>">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
              </form>
            </td>
          </tr>
          <?php
        }else{
        ?>
        <tr>
          <td><a href="<?php echo $URL ?>"><img src="<?php echo $cover ?>" class="winkelmand_img"></a></td>
          <td><?php echo $titel ?></td>
          <td><?php echo $omschr ?><td>
          <td>
            <form method="post" action="?action=delete&code=<?php echo $id ?>">
              <button type="submit" class="btn btn-success">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
              </button>
            </form>
          </td>
        </tr>
        <?php
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
  echo "405 - GEEN TOEGANG";
}
