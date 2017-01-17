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
        <h1>KLANT BLOKKEREN</h1>
        <?php
        //Haal alle klanten op
        $rol = 1;
        $stmt = DB::conn()->prepare("SELECT id FROM `Persoon` WHERE rolid=?");
        $stmt->bind_param('i', $rol);
        $stmt->execute();
        $stmt->bind_result($id);

        $klanten = array();

        while($stmt->fetch()){
          $klant[] = $id;
        }

        $stmt->close();

        foreach($klant as $i){
          $stmt = DB::conn()->prepare("SELECT naam, telefoonnummer, email FROM `Persoon` WHERE id=?");
          $stmt->bind_param('i', $i);
          $stmt->execute();
          $stmt->bind_result($naam, $telefoonnummer, $email);
          $stmt->fetch();
          $stmt->close();


          echo "ID: " . $i . "<br>";
          echo "Naam: " . $naam . "<br>";
          echo "Telefoonnummer: " . $telefoonnummer . "<br>";
          echo "Email: " . $email  . "<br><br>";
        }
        ?>
  <?php
DB::conn()->close();
  }
}
