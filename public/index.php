<?php
require(__dir__ . '/../vendor/autoload.php');
require(__dir__ . '/../resources/config.php');
require_once(TEMPLATES_PATH . "/head.php");
require_once(TEMPLATES_PATH . "/footer.php");
?>
<div class="container">
  <div class="content">
    <p>test of dit nu goed gaat</p>
  </div>
</div>

<?php

$klein = new \Klein\Klein();

$klein->respond('GET', '/test', function () {
    return("TEST");
});

$klein->dispatch();
?>
