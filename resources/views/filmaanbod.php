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
    if($_GET['action'] == 'add'){
        $product_cart_id = $_SESSION['cart_item']['id'];
        $id = $_GET['code'];

        //VOEG TO AAN `ORDER`
        $order_id = rand(1, 2100);
        $bedrag = 7.50;
        $klant = $_SESSION['login']['0'];
        $besteld = 0;
        $huidigeWeek = date('d-m-Y');
        $volgendeWeek = date('d-m-Y', strtotime("+7 days"));
        $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, afleverdatum, ophaaldatum, bedrag, besteld) VALUES (?, ?, ?, ?, ?, ?)");
        $cart_stmt->bind_param("iissdi", $order_id, $klant, $huidigeWeek, $volgendeWeek, $bedrag, $besteld );
        $cart_stmt->execute();
        $cart_stmt->close();


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

        ?>
          <div class="filmThumbnail filmAanbodFilm col-md-3">
                  <a href="/">
                      <div class="thumb">
                          <a href=<?php echo"$url" ?>>
                          <img src=<?php echo"$cover" ?> class="thumb_img"/></a>
                          <h2 class="textfilmaanbod"><?php echo "$titel"?> </h2>
                              <form method="post" action="?action=add&code=<?php echo $id ?>"><?php
                                  if(!empty($_SESSION["login"])){
                                      ?>
                                      <button type="submit" class="btn btn-success bestel filmaanbodbestel"><li class="fa fa-shopping-cart"></li></button>
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
