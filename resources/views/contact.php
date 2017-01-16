<div class="panel panel-default">
  <div class="panel-body">

    <div>
        <div style="float:left">
        <h2> Contactformulier:</h2>
    <form method="post">
      <input type="text" name="naam" placeholder="Naam"> <br>
        <input type="text" name="email" placeholder="Email"> <br>

        <textarea name="bericht" rows="10" cols="50" placeholder="Uw bericht"></textarea> <br>
      <input type="submit" name="submit" value="VERSTUUR">
    </form>
        </div>
      <div style="float:right">
          <h2>Contactgegevens: </h2>
          TempoVideo <br>
          info@tempovideo.nl <br>
          088-1234567
      </div>
    </div>
  </div>

</div>

<?php
echo($_POST['naam']);
echo($_POST['email']);
echo($_POST['bericht']);