<h1>REGISTREER</h1>

<form method="post">
  <input type="text" name="naam" placeholder="Naam" class="form-control">
  <input type="text" name="adres" placeholder="Adres" class="form-control">
  <input type="text" name="postcode" placeholder="Postcode" class="form-control">
  <input type="text" name="woonplaats" placeholder="Woonplaats" class="form-control">
  <input type="text" name="telefoonnummer" placeholder="Telefoonnummer" class="form-control">
  <input type="email" name="email" placeholder="Email" autocomplete="off" class="form-control">
  <input type="password" name="wachtwoord" placeholder="Wachtwoord" autocomplete="off" class="form-control">

  <input type="submit" name="submit" value="REGISTREER">
</form>

<?php

if(!empty($_POST)){
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
  echo "Wachtwoord aangemaakt <br>";

  //ACCOUNT GEGEVENS
  //Gegevens invoeren in Klant tabel
  $stmt = DB::conn()->prepare("INSERT INTO Klant (naam, adres, postcode, woonplaats, telefoonnummer, email, wachtwoordid) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssi", $naam, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $id);
  $stmt->execute();
  echo "Account aangemaakt";

  //Connecties afsluiten
  $stmt->close();
  DB::conn()->close();
}
