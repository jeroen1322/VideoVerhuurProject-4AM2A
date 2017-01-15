<?php
$email = $_POST['email'];
$wachtwoord = $_POST['wachtwoord'];

if(!empty($_POST)){
  if($email && $wachtwoord != ''){
    //Pak het wachtwoordid dat bij de ingevoerde email hoort
    $stmt = DB::conn()->prepare("SELECT wachtwoordid FROM Persoon WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt->bind_result($wachtwoordid);
    $stmt->fetch();
    $stmt->close();

    //Pak het id van de klant dat bij de ingevoerde email hoort
    $stmt = DB::conn()->prepare("SELECT id FROM Persoon WHERE email=?");
    $stmt->bind_param("s", $email);    //RolId


    $stmt->execute();

    $stmt->bind_result($klantId);
    $stmt->fetch();
    $stmt->close();

    //Pak de naam van de klant dat bij de ingevoerde email hoort
    $name_stmt = DB::conn()->prepare("SELECT naam FROM Persoon WHERE id=?");
    $name_stmt->bind_param("i", $klantId);
    $name_stmt->execute();

    $name_stmt->bind_result($naam);
    $name_stmt->fetch();
    $name_stmt->close();

    //Haal het wachtwoord op dat bij het ID hoort
    $ww_stmt = DB::conn()->prepare("SELECT wachtwoord FROM Wachtwoord WHERE id=?");
    $ww_stmt->bind_param("i", $wachtwoordid);
    $ww_stmt->execute();

    $ww_stmt->bind_result($ww);
    $ww_stmt->fetch();
    $ww_stmt->close();

    //Controlleer het opgehaalde wachtwoord met het ingevoerde wachtwoord overeenkomt
    if (password_verify($wachtwoord, $ww)) {
        $_SESSION['login'] = array();
        $_SESSION['login'][] = $klantId; //Zet de session die we kunnen checken in de functie isIngelogd();
        $_SESSION['login'][] = $naam;
        ?>
        <script>window.location.replace("/");</script>
        <?php
    } else {
        echo '<div class="alert">Deze email en wachtwoord combinatie is niet bij ons geregistreerd.</div>';
    }

    DB::conn()->close();
  }else{
    echo '<div class="alert">Controlleer of u alle velden correct heeft ingevuld.</div>';
  }
}
?>
<div class="panel panel-default">
  <div class="panel-body login-panel">
    <h1>LOGIN</h1>
    <form method="post">
      <input type="email" name="email" placeholder="Email" class="form-control">
      <input type="password" name="wachtwoord" placeholder="Wachtwoord" class="form-control">
      <input type="submit" name="submit" class="btn btn-primary form-knop" value="LOGIN">
    </form>
  </div>
</div>
