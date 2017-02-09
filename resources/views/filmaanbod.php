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
$dis = false;
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

if(!empty($_GET['action'])){
    echo "test";
    if($_GET['action'] == 'add') {
        $_SESSION['cart_item'] = array();
        $_SESSION['cart_item']['id'] = $_GET['code'];
        $product_cart_id = $_SESSION['cart_item']['id'];
        // echo $product_cart_id;

        $klant = $_SESSION['login']['0'];
        $besteld = 0;
        $afhandeling = 0;
        $huidigeWeek = date('d-m-Y');
        $volgendeWeek = date('d-m-Y', strtotime("+7 days"));

        $cart_stmt = DB::conn()->prepare("select count(o.id) from `Order` o where o.klantid =? and ifnull(besteld, false) = false;");
        $cart_stmt->bind_param("i", $klantId);
        $cart_stmt->execute();
        $cart_stmt->bind_result($countorder);
        $cart_stmt->fetch();
        $cart_stmt->close();

        if ($countorder == 0) {
            $order_id = rand(1, 2100);
            $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, afhandeling, orderdatum, besteld) VALUES (?, ?, ?, ?, ?)");
            $cart_stmt->bind_param("iiisi", $order_id, $klant, $afhandeling, $huidigeWeek, $besteld);
            $cart_stmt->execute();
            $cart_stmt->close();
        }
        $orderid_stmt = DB::conn()->prepare("select id FROM `Order` WHERE klantid =? AND besteld = 0");
        $orderid_stmt->bind_param("i", $klantId);
        $orderid_stmt->execute();
        $orderid_stmt->bind_result($order_id);
        $orderid_stmt->fetch();
        $orderid_stmt->close();



        //VOEG TOE AAN `ORDERREGEL`
        $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
        $exm_stmt->bind_param("i", $titel);
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
<div class="filmaanbod">
<?php
if(!empty($titel)){
      foreach($film_titel as $i){
        $stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM `Film` where id=?");
        $stmt->bind_param("i", $i);
        $stmt->execute();
        $stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);

        $stmt->fetch();
        $stmt->close();
        $url =  "/film/" . $id;
        $titel = str_replace('_', ' ', $titel);
        $titel = strtoupper($titel);
        $cover = "/cover/" . $img;

        $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
        $exm_stmt->bind_param("i", $i);
        $exm_stmt->execute();
        $exm_stmt->bind_result($exemplaar_id);
        $beschikbaar = array();
        while($exm_stmt->fetch()){
            $beschikbaar[] = $exemplaar_id;
        }
        $exm_stmt->close();
        $count = count($beschikbaar);

        ?>
          <div class="filmThumbnail filmAanbodFilm col-md-3">
                  <a href="/">
                      <div class="thumb">
                          <a href=<?php echo"$url" ?>>
                          <img src=<?php echo"$cover" ?> class="thumb_img filmaanbod_img"/></a>
                          <h2 class="textfilmaanbod"><?php echo "$titel"?> </h2>
                              <form method="post" action="?action=add&code=<?php echo $i ?>">
                                <?php
                                  if(!empty($_SESSION["login"]) && $count != 0){
                                      ?>
                                      <button type="submit" class="btn btn-success bestel filmaanbodbestel"><li class="fa fa-shopping-cart"></li></button>
                                      <?php
                                  }elseif($count == 0){
                                    ?>
                                    <button type="submit" class="btn btn-success bestel filmaanbodbestel" disabled><b>UITVERKOCHT</b></button>
                                    <?php
                                  }else{
                                    ?>
                                    <button type="submit" class="btn btn-success bestel filmaanbodbestel" disabled><li class="fa fa-shopping-cart"></li></button>
                                    <?php
                                  }
                                  ?>
                              </form>

                      </div>
                  </a>
              </div>
        <?php
    }
}
  DB::conn()->close();
  ?>
    </div>
  </div>
</div>
