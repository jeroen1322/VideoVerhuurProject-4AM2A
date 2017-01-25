<?php
if(!empty($_POST)){
  if($_POST['naam'] && $_POST['adres'] && $_POST['postcode']  && $_POST['woonplaats'] && $_POST['telefoonnummer'] && $_POST['email'] && $_POST['wachtwoord'] != ''){
    //Ingevoerde gegevens aan variabelen assignen
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];

    //Willekeurig id
    $id = rand(1, 1100);

    // WACHTWOORD
    //Hash Wachtwoord
    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    //Wachtwoord invoeren in tabel Wachtwoord, met een random id
    $passw_stmt = DB::conn()->prepare("INSERT INTO Wachtwoord (id, wachtwoord) VALUES (?, ?)");
    $passw_stmt->bind_param("is", $id, $hash);
    $passw_stmt->execute();

    //ACCOUNT GEGEVENS

    //RolId
    // 1 = klant
    // 2 = bezorger
    // 3 = baliemedewerker
    // 4 = eigenaar
    // 5 = geblokkeerd

    //RolId
    $rolid = 1;

    //Gegevens invoeren in Persoon tabel
    $stmt = DB::conn()->prepare("INSERT INTO Persoon (naam, adres, postcode, woonplaats, telefoonnummer, email, wachtwoordid, rolid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $naam, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $id, $rolid);
    $stmt->execute();

    echo "<div class='succes'>Account aangemaakt.</div>";

    header("Refresh:0; url=/login");
    //Connecties afsluiten
    $stmt->close();
    DB::conn()->close();
  }else{
    echo "<div class='alert'>Controlleer of u alle informatie correct heeft ingevuld.</div>";
  }
}
?>

<div class="panel panel-default">
  <div class="panel-body registreer-panel">
    <h1>REGISTREER</h1>
    <form method="post">
      <input type="text" name="naam" placeholder="Naam" class="form-control" required>
      <input type="text" name="adres" placeholder="Adres" class="form-control" required>
      <input type="text" name="postcode" placeholder="Postcode" class="form-control"  required>
      <input type="text" name="woonplaats" placeholder="Woonplaats" class="form-control"  required>
      <input type="text" name="telefoonnummer" placeholder="Telefoonnummer" class="form-control"  required>
      <input type="email" name="email" placeholder="Email" autocomplete="off" class="form-control"  required>
      <input type="password" name="wachtwoord" placeholder="Wachtwoord" autocomplete="off" class="form-control"  required>

      <input type="submit" name="submit" class="btn btn-primary form-knop" value="REGISTREER">
    </form>
  </div>
</div>
