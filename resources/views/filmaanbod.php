<div class="panel panel-default">
    <div class="panel-body">
        <h1></h1>
        <div class="filmThumbnail col-md-3">
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
        $stmt = DB::conn()->prepare("SELECT * FROM `Film` where id=?");
        $stmt->bind_param("i", $i);
        $stmt->execute();
        $stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
        $stmt->fetch();
        $stmt->close();
        $cover = "/cover/" . $img;
        ?>

                  <a href="/">
                      <div class="thumb">
                          <a href="#">
                          <img src=<?php echo"$cover" ?> class="thumb_img" style="align-content:center; width:100px;"/></a>
                          <h2><?php echo "$titel"?></h2>
                      </div>
                  </a>
              </div>

<?php }} ?>
    </div>
</div>
