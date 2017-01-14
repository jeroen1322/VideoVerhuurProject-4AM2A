<?php

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM Film WHERE titel=?");
$stmt->bind_param("s", $this->filmNaam);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();

$cover = "/cover/" . $img;
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);

DB::conn()->close();


if(!empty($_GET)){

  $ophaaldatum = "13-01-2017";
  $afhaalDatum = "20-01-2017";
  
  $_SESSION['cart_item'] = array();
  $_SESSION['cart_item']['id'] = $_GET['code'];

  echo $_SESSION['cart_item']['id'];
}



// case "add":
//   if(!empty($_POST["quantity"])) {
//


?>
<div class="row">
    <div class="col-md-10 col-md-offset-1 details">
      <a class="btn btn-success terug_button" href="/film/aanbod">
        <li class="fa fa-arrow-left filmaanbod-terug"></li>Filmaanbod
      </a>
        <div class="filmDetails">
          <div class="panel panel-default">
            <div class="panel-body">
              <img src="<?php echo $cover ?>" class="img-responsive cover"/>
              <h1><b><?php echo $titel ?></b></h1>
              <h3>Omschrijving</h3>
              <p><?php echo $omschr ?></p>
              <h3>Acteurs</h3>
              <p><?php echo $acteur ?></p>
              <h3>Genre</h3>
              <p><?php echo $genre ?></p>
              <form method="post" action="?action=add&code=<?php echo $id ?>">
                <input type="submit" class="btn btn-success bestel" value="Bestel">
              </form>
            </div>
          </div>
      </div>
    </div>
</div>
