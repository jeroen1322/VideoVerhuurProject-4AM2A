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
    if(!empty($_GET)){
      $code = $_GET['code'];
      $action = $_GET['action'];
      if($action == 'block'){
        $blockRol = 5;

        $stmt = DB::conn()->prepare("UPDATE `Persoon` SET rolid=? WHERE id=?;");
        $stmt->bind_param("ii", $blockRol, $code);
        $stmt->execute();
        $stmt->close();
        header("Refresh:0; url=/eigenaar/klant_blokkeren");
      }elseif($action == 'unblock'){
        $unblockRol = 1;

        $stmt = DB::conn()->prepare("UPDATE `Persoon` SET rolid=? WHERE id=?;");
        $stmt->bind_param("ii", $unblockRol, $code);
        $stmt->execute();
        $stmt->close();
        header("Refresh:0; url=/eigenaar/klant_blokkeren");
      }
    }
    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h1>KLANT BLOKKEREN</h1>
        <table class="table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Naam</th>
              <th>Telefoonnummer</th>
              <th>Email</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
        <?php
        //Haal alle klanten op
        $rol1 = 1;
        $rol2 = 5;
        $stmt = DB::conn()->prepare("SELECT id FROM `Persoon` WHERE rolid=? OR rolid=?");
        $stmt->bind_param('ii', $rol1, $rol2);
        $stmt->execute();
        $stmt->bind_result($id);

        $klanten = array();

        while($stmt->fetch()){
          $klant[] = $id;
        }

        $stmt->close();

        foreach($klant as $i){
          $stmt = DB::conn()->prepare("SELECT naam, telefoonnummer, email, rolid FROM `Persoon` WHERE id=?");
          $stmt->bind_param('i', $i);
          $stmt->execute();
          $stmt->bind_result($naam, $telefoonnummer, $email, $rolId);
          $stmt->fetch();
          $stmt->close();
          ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $naam ?></td>
            <td><?php echo $telefoonnummer ?></td>
            <td><?php echo $email ?></td>
            <td>
              <?php
                if($rolId === 1){
                  echo "NIET GEBLOKKEERD";
                }elseif($rolId === 5){
                  echo "<b>GEBLOKKEERD</b>";
                }
              ?>
            </td>
            <td>
              <?php
                if($rolId === 1){
                  ?>
                  <form method="post" action="?action=block&code=<?php echo $i ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                    </button>
                  </form>
                  <?php
                }elseif($rolId === 5){
                  ?>
                  <form method="post" action="?action=unblock&code=<?php echo $i ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-ban unblock" aria-hidden="true"></i>
                    </button>
                  </form>
                  <?php
                }
                ?>
            </td>
          </tr>
          <?php
        }

        DB::conn()->close();
  }
  ?>
  </tbody>
  </table>
<?php
}
