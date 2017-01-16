<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  function isEigenaar($klantId){
    if($klantId === 1){
      return true;
    }else{
      return false;
    }
  }
  if(isEigenaar($klantId)){
    ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <h1>EIGENAAR OVERZICHT</h1>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="dummy"></div>
            <a href="/eigenaar/film_toevoegen" class="thumbnail purple">FILM TOEVOEGEN</a>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="dummy"></div>
            <a href="/eigenaar/film_verwijderen" class="thumbnail purple">FILM VERWIJDEREN</a>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="dummy"></div>
            <a href="#x" class="thumbnail purple">FILM INFORMATIE BEHEREN</a>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-6">
            <div class="dummy">
              <a href="#x" class="thumbnail purple">KLANT BLOKKEREN</a>
            </div>
        </div>
      </div>
    </div>
  <?php
  }else{
    echo "404";
  }
}else{
  echo "404";
}
