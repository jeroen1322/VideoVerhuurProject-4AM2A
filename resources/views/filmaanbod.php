<div class="panel panel-default">
    <div class="panel-body">
        <h1></h1>

<?php
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
        $titel = str_replace('_', ' ', $titel);
        $titel = strtoupper($titel);
        $cover = "/cover/" . $img;
        ?>
          <div class="filmThumbnail col-md-3">
                  <a href="/">
                      <div class="thumb">
                          <a href="#">
                          <img src=<?php echo"$cover" ?> class="thumb_img"/></a>
                          <h2 class="textfilmaanbod"><?php echo "$titel"?></h2>
                      </div>
                  </a>
              </div>

<?php }} ?>
    </div>
</div>
