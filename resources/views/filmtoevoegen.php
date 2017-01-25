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
        <div class="btn-group admin">
          <a href="/eigenaar/overzicht" class="btn btn-primary admin_menu">OVERZICHT</a>
          <a href="/eigenaar/film_toevoegen" class="btn btn-primary actief admin_menu">FILM TOEVOEGEN</a>
          <a href="/eigenaar/film_verwijderen" class="btn btn-primary admin_menu">FILM VERWIJDEREN</a>
          <a href="/eigenaar/film_aanpassen" class="btn btn-primary admin_menu">FILM INFO BEHEREN</a>
          <a href="/eigenaar/klant_blokkeren" class="btn btn-primary admin_menu">KLANT BLOKKEREN</a>
        </div>
        <h1>FILM TOEVOEGEN</h1>
        <form method="post" enctype="multipart/form-data">
          <input type="text" name="titel" placeholder="Titel" class="form-control" autocomplete="off" required>
          <input type="text" name="acteur" placeholder="Acteurs" class="form-control" autocomplete="off" required>
          <input type="text" name="oms" placeholder="Omschrijving" class="form-control" autocomplete="off" required>
          <input type="text" name="genre" placeholder="Genre" class="form-control" autocomplete="off" required>
          <input type="file" name="img" placeholder="FOTO" class="form-control" autocomplete="off" required>

          <input type="submit" class="btn btn-succes form-knop" name="submit" value="VOEG TOE">
        </form>
      </div>
      </div>
    </div>
    <?php
  }else{
    echo "404";
  }
}else{
  header("Refresh:0; url=/login");
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
  $rand = rand(1, 9999);
  $name = $uploadName . "_" . $rand . "." . $imageFileType;
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
  header("Refresh:0; url=/film/$uploadName");
  }
