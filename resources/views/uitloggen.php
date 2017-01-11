<h1>UITLOGGEN</h1>
<?php
  session_unset($_SESSION['login']);
?>
<script>window.location.replace("/");</script>
