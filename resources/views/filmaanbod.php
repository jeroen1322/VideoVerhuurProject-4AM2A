
<?php

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT * FROM Film");
$stmt->bind_param("s", $this->filmNaam);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();

$cover = "../resources/storage/film_foto/" . $img. ".jpg";
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);
DB::conn()->close();
?>

<div class="panel panel-default">
  <div class="panel-body">
    <h1></h1>

      <div class="filmThumbnail col-md-3">
          <a href=#>
              <div class="thumb">
                  <img src=<?php echo"$cover" ?> class="thumb_img"/>
                  <h2><?php echo "$titel"?></h2>
              </div>
          </a>
      </div>

  </div>
</div>
