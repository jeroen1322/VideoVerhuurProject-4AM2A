
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
  if(isEigenaar($klantRolId)){
    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h1>FILM TOEVOEGEN</h1>

        <form method="post" enctype="multipart/form-data">
          <input type="text" name="titel" placeholder="Titel" class="form-control" autocomplete="off">
          <input type="text" name="acteur" placeholder="Acteurs" class="form-control" autocomplete="off">
          <input type="text" name="oms" placeholder="Omschrijving" class="form-control" autocomplete="off">
          <input type="text" name="genre" placeholder="Genre" class="form-control" autocomplete="off">
          <input type="file" name="img" placeholder="FOTO" class="form-control" autocomplete="off">

          <input type="submit" class="btn btn-succes form-knop" name="submit" value="VOEG TOE">
        </form>
      </div>
    </div>
    <?php
  }else{
    echo "404";
  }
}else{
  echo "404";
}


if(!empty($_POST)){
  $titel = $_POST['titel'];
  $acteur = $_POST['acteur'];
  $oms = $_POST['oms'];
  $genre = $_POST['genre'];
  $img = $_FILES['img'];
  $uploadName = $titel;
  $uploadName = str_replace(' ', '_', $uploadName);
  $uploadName = strtolower($uploadName);

  $target_dir = FOTO."/";
  $target_file = basename($_FILES["img"]["name"]);
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  $name = $uploadName . "." . $imageFileType;
  $target_place = $target_dir . $name;

  if(move_uploaded_file($_FILES['img']['tmp_name'], $target_place)){

  }else{
    echo "Er was een fout tijdens het uploaden van de foto.";
  }

  //Gegevens invoeren in Film tabel
  $stmt = DB::conn()->prepare("INSERT INTO Film (titel, acteur, omschr, genre, img) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $uploadName, $acteur, $oms, $genre, $name);
  $stmt->execute();



  $stmt->close();

  //EXEMPLAAR
  $ex_stmt = DB::conn()->prepare("SELECT id FROM Film WHERE titel=?");
  $ex_stmt->bind_param("s", $uploadName);
  $ex_stmt->execute();
  $ex_stmt->bind_result($id);
  $ex_stmt->fetch();
  $ex_stmt->close();

  for($i = 1; $i < 11; $i++){
    $statusid = 1;
    $aantalVerhuur = 0;
    $add_ex_stmt = DB::conn()->prepare("INSERT INTO Exemplaar (filmid, statusid, aantalVerhuur) VALUES (?, ?, ?)");
    $add_ex_stmt->bind_param("iii", $id, $statusid, $aantalVerhuur);
    $add_ex_stmt->execute();
    $add_ex_stmt->close();
  }
  echo "<div class='succes'>FILM TOEGEVOEGD!</div>";
  DB::conn()->close();
  ?>
  <script>window.location.replace("/film/<?php echo $uploadName ?>" );</script>
  <?php
}
