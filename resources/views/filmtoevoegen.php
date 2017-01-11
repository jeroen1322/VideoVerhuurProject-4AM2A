<div class="panel panel-default">
  <div class="panel-body">
    <h1>FILM TOEVOEGEN</h1>

    <!--
    -Titel
    -Acteur(s)
    -Omschrijving
    -Genre
    -Img
    -->
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="titel" placeholder="Titel" class="form-control">
      <input type="text" name="acteur" placeholder="Acteurs" class="form-control">
      <input type="text" name="oms" placeholder="Omschrijving" class="form-control">
      <input type="text" name="genre" placeholder="Genre" class="form-control">
      <input type="file" name="img" placeholder="FOTO" class="form-control">

      <input type="submit" name="submit" value="VOEG TOE">
    </form>
  </div>
</div>

<?php

if(!empty($_POST)){
  $titel = $_POST['titel'];
  $acteur = $_POST['acteur'];
  $oms = $_POST['oms'];
  $genre = $_POST['genre'];
  $img = $_FILES['img'];
  $uploadName = $titel;
  $uploadName = str_replace(' ', '', $uploadName);

  $target_dir = FOTO."/";
  $target_file = basename($_FILES["img"]["name"]);
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

  $target_place = $target_dir . $uploadName . "." . $imageFileType;

  if(move_uploaded_file($_FILES['img']['tmp_name'], $target_place)){

  }else{
    echo "Er was een fout tijdens het uploaden van de foto.";
  }

  //Gegevens invoeren in Film tabel
  $stmt = DB::conn()->prepare("INSERT INTO Film (titel, acteur, omschr, genre, img) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $titel, $acteur, $oms, $genre, $uploadName);
  $stmt->execute();

  $stmt->close();
  DB::conn()->close();
}
