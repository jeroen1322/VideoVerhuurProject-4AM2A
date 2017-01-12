<?php

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM Film WHERE titel=?");
$stmt->bind_param("s", $this->filmNaam);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();
$stmt->close();

$cover = "/cover/" . $img;
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);

DB::conn()->close();


?>
<div class="row">
    <div class="col-md-10 col-md-offset-1 details">
      <a class="btn btn-success terug_button" href="/film/aanbod">
        <li class="fa fa-arrow-left filmaanbod-terug"></li>Filmaanbod
      </a>
        <div class="filmDetails">
          <div class="panel panel-default">
            <div class="panel-body">
              <img src="<?php echo $cover ?>" class="img-responsive cover" />
              <h1><b><?php echo $titel ?></b></h1>
              <h3>Omschrijving</h3>
              <p><?php echo $omschr ?></p>
              <h3>Acteurs</h3>
              <p><?php echo $acteur ?></p>
              <h3>Genre</h3>
              <p><?php echo $genre ?></p>
            </div>
          </div>
      </div>
    </div>
</div>
