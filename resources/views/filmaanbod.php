
<?php

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT img FROM Film");
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();

$cover = "/cover/" . $titel;
DB::conn()->close();
?>

<div class="panel panel-default">
  <div class="panel-body">
    <h1></h1>
      <div class="filmThumbnail col-md-3">
          <a href="/">
              <div class="thumb">
                  <a href="#">
                  <img src=<?php echo"$cover" ?> class="thumb_img"/></a>
                  <h2><?php echo "$titel"?></h2>
              </div>
          </a>
      </div>
  </div>
</div>
