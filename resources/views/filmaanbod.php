<div class="panel panel-default">
    <div class="panel-body">
        <h1></h1>

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
    function isGeblokkeerd($klantRolId)
    {
        if ($klantRolId === 5) {
            return true;
        } else {
            return false;
        }
    }
}
if(!empty($_SESSION['login'])){
    if(isGeblokkeerd($klantRolId)){
        $dis = true;
    }else{
        $dis = false;
    }
}
//test
//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id FROM `Film`");
$stmt->execute();
$stmt->bind_result($titel);
$film_id = array();
while($stmt->fetch()){
    $film_titel[] = $titel;
}

$stmt->close();
if(!empty($titel)){
      foreach($film_titel as $i){
        $stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` where id=?");
        $stmt->bind_param("i", $i);
        $stmt->execute();
        $stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);

        $stmt->fetch();
        $stmt->close();
        $url =  "/film/" . $titel;
        $titel = str_replace('_', ' ', $titel);
        $titel = strtoupper($titel);
        $cover = "/cover/" . $img;
          if(!empty($_GET['action'])){
              if($_GET['action'] == 'add'){
                  $_SESSION['cart_item'] = array();
                  $_SESSION['cart_item']['id'] = $_GET['code'];
                  $product_cart_id = $_SESSION['cart_item']['id'];
                  // echo $product_cart_id;

                  //VOEG TO AAN `ORDER`
                  $order_id = rand(1, 2100);
                  $bedrag = 7.50;
                  $klant = $_SESSION['login']['0'];
                  $besteld = 0;
                  $huidigeWeek = date('d-m-Y');
                  $volgendeWeek = date('d-m-Y', strtotime("+7 days"));
                  $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, afleverdatum, ophaaldatum, bedrag, besteld) VALUES (?, ?, ?, ?, ?, ?)");
                  $cart_stmt->bind_param("iissii", $order_id, $klant, $huidigeWeek, $volgendeWeek, $bedrag, $besteld );
                  $cart_stmt->execute();
                  $cart_stmt->close();

                  //VOEG TOE AAN `ORDERREGEL`
                  $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
                  $exm_stmt->bind_param("i", $id);
                  $exm_stmt->execute();
                  $exm_stmt->bind_result($exemplaar_id);
                  $exm_stmt->fetch();
                  $exm_stmt->close();

                  $exm_stmt = DB::conn()->prepare("UPDATE `Exemplaar` SET statusid=2 WHERE id=?");
                  $exm_stmt->bind_param("i", $exemplaar_id);
                  $exm_stmt->execute();
                  $exm_stmt->close();

                  $or_stmt = DB::conn()->prepare("INSERT INTO `Orderregel` (exemplaarid, orderid) VALUES (?, ?)");
                  $or_stmt->bind_param("ii", $exemplaar_id, $order_id);
                  $or_stmt->execute();
                  $or_stmt->close();
                  $e = str_replace(' ', '_', $titel);
                  header("Refresh:0; url=/film/aanbod");

              }

          }
        ?>
          <div class="filmThumbnail col-md-3">
                  <a href="/">
                      <div class="thumb">
                          <a href=<?php echo"$url" ?>>
                          <img src=<?php echo"$cover" ?> class="thumb_img"/></a>
                          <h2 class="textfilmaanbod"><?php echo "$titel"?>
                              <form method="post" action="?action=add&code=<?php echo $id ?>"><?php
                                  if($dis){
                                      ?>
                                      <input type="submit" class="btn btn-success bestel" value="Bestel" disabled>
                                      <?php
                                  }elseif(empty($_SESSION['login'])){
                                      ?>
                                      <input type="submit" class="btn btn-success bestel" value="Bestel" disabled><br><br><br>
                                      <h5><b>U moet <a href="/login">ingelogd</a> zijn om te kunnen bestellen</b></h5>
                                      <?php
                                  }else{
                                  ?>
                                  <input type="submit" class="btn btn-success bestel" value="Bestel">

                          <?php
                          }
                          ?>
                              </form>

                      </div>
                  </a>
              </div>

<<<<<<< HEAD
<?php }} DB::conn()->close(); ?>
=======
<?php
    }
}
DB::conn()->close();
?>
>>>>>>> master
    </div>
</div>
