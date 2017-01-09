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

if(!empty($_POST)){
  $naam = $_POST['naam'];
  $adres = $_POST['adres'];
  $postcode = $_POST['postcode'];
  $woonplaats = $_POST['woonplaats'];
  $telefoonnummer = $_POST['telefoonnummer'];
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];

  // prepare and bind
  $stmt = DB::conn()->prepare("INSERT INTO Klant (naam, adres, postcode, woonplaats, email) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $naam, $adres, $postcode, $woonplaats, $email);

  // set parameters and execute
  // $naam = "Jeroen";
  $adres = "Oeverstraat 21";
  $email = "contact@jeroengrooten.nl";
  $stmt->execute();

  echo "New records created successfully";

  $stmt->close();
  DB::conn()->close();
}
// PASSWORD HASHEN
 $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
// $hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
  echo $hash;
// if (password_verify('rasmuslerdorf', $hash)) {
//     echo 'Password is valid!';
// } else {
//     echo 'Invalid password.';
// }
