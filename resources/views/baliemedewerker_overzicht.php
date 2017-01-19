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
    }}
if(isEigenaar($klantRolId)){
    ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="btn-group admin">
                    <a href="/baliemedewerker/inkomendeorders" class="btn btn-primary actief admin_menu">BINNENGEKOMEN ORDERS</a>
                    <a href="/eigenaar/film_verwijderen" class="btn btn-primary admin_menu">FILM VERWIJDEREN</a>
                    <a href="/eigenaar/film_aanpassen" class="btn btn-primary admin_menu">FILM INFO BEHEREN</a>
                </div>
                <h1> Binnengekomen orders</h1>

            </div>
        </div>
<?php } ?>