<div class="panel panel-default">
  <div class="panel-body">

    <div>
        <div style="float:left">
        <h2> Contactformulier:</h2>
    <form method="post">
      <input type="text" name="naam" placeholder="Naam" required> <br>
        <input type="text" name="email" placeholder="Email" required> <br>

        <textarea name="bericht" class="col-xs-8" cols="50" placeholder="Uw bericht" required></textarea> <br><br><br>
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