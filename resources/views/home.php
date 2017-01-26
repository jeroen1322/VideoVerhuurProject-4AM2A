<?php
$stmt = DB::conn()->PREPARE("SELECT id FROM Film");
$stmt->execute();
$stmt->bind_result($id);
$film_id = array();
while($stmt->fetch()){
  $film_id[] = $id;
}
$stmt->close();

?>
<div class="panel panel-default">
  <div class="panel-body">
    <h1 class="netToegevoegd">RECENT TOEGEVOEGDE FILMS</h1>
      <div class="nieuw_film_slider">
        <?php
        if(!empty($film_id)){
          foreach($film_id as $i){
            $stmt = DB::conn()->PREPARE("SELECT id,titel, img FROM Film WHERE id=?");
            $stmt->bind_param('i', $i);
            $stmt->execute();
            $stmt->bind_result($id, $titel, $img);
            $stmt->fetch();
            $stmt->close();
            $cover = "/cover/".$img;
            $url = $id;
            $titel = str_replace('_', ' ', $titel);
            $titel = strtoupper($titel);
            ?>
            <div class="filmThumbnail filmAanbodFilm col-md-3 ">
                    <a href="/film/<?php echo $url ?>">
                        <div class="thumb">
                            <img src=<?php echo"$cover" ?> class="thumb_img nieuweThumb nieuweFilm"/></a>
                            <h2 class="textfilmaanbod"><?php echo "$titel"?></h2>
                        </div>
                    </a>
                </div>
            <?php
          }
        }
        ?>
      </div>
  </div>
</div>
<?php
DB::conn()->close();
