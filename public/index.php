<?php
require(__dir__ . '/../vendor/autoload.php');
require(__dir__ . '/../resources/config.php');
require_once(TEMPLATES_PATH . "/head.php");
require_once(TEMPLATES_PATH . "/footer.php");
?>
<div class="container">
  <div class="content">

  </div>
</div>

<?php

$klein = new \Klein\Klein();

$klein->respond('GET', '/', function () {
    return("HOME");
});
$klein->respond('GET', '/filmaanbod', function () {
    return("FILMAANBOD");
});
$klein->respond('GET', '/over_ons', function () {
    return("OVER ONS");
});
$klein->respond('GET', '/contact', function () {
    return("CONTACT");
});

$klein->dispatch();
?>
