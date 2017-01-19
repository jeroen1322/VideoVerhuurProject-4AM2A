<?php
if(!empty($_SESSION['login'])){
    $klantId = $_SESSION['login'][0];
    $klantNaam = $_SESSION['login'][1];
    $klantRolId = $_SESSION['login'][2];
    function isEigenaar($klantRolId){
        if($klantRolId === 3){
            return true;
        }else{
            return false;
        }
    }
    if(isEigenaar($klantRolId)){
?>
<div class="panel panel-default">
    <div class="panel-body">
        <h1></h1>

        <?php
        //test
        //Pak de foto van de film
        $stmt = DB::conn()->prepare("SELECT id FROM `Order`");
        $stmt->execute();
        $stmt->bind_result($id);
        $order_id = array();
        while($stmt->fetch()){
            $order_id[] = $id;
        }

        $stmt->close();
        if(!empty($id)){
            foreach($order_id as $i){
                $stmt = DB::conn()->prepare("SELECT id, afhandeling FROM `Order` where id=? and afhandeling = false");
                $stmt->bind_param("i", $i);
                $stmt->execute();
                $stmt->bind_result($id, $afhandeling);

                $stmt->fetch();
                $stmt->close();
                //$url =  "/film/" . $titel;
                //$titel = str_replace('_', ' ', $titel);
                //$titel = strtoupper($titel);
                //$cover = "/cover/" . $img;

                ?>
                <div class="filmThumbnail col-md-3">
                    <a href="/">
                        <div class="">
                            <h2 class="textfilmaanbod"><?php echo $id?> </h2>
                            <?php

                            if($afhandeling === 0){
                                ?>
                                <form method="post" action="?action=unblock&code=<?php echo $i ?>">
                                    <button type="submit">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                    </a>
                </div>

            <?php
            }
          }
          else {
              echo "geen data";
          }
        ?>
    </div>
</div>
<?php
  }
}else{
  header("Refresh:0; url=/login");
}
?>
