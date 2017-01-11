<div class="panel panel-default">
  <div class="panel-body">
    <h1>CONTACT</h1>
    <form method="post">
      <input type="text" name="naam" placeholder="Naam">
      <input type="submit" name="submit" value="VERSTUUR">
    </form>
  </div>
</div>

<?php
echo($_POST['naam']);
