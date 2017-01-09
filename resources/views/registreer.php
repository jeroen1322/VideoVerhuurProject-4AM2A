<h1>Registreer</h1>

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
$naam = $_POST['naam'];
$adres = $_POST['adres'];
$postcode = $_POST['postcode'];
$woonplaats = $_POST['woonplaats'];
$telefoonnummer = $_POST['telefoonnummer'];
$email = $_POST['email'];
$wachtwoord = $_POST['wachtwoord'];
