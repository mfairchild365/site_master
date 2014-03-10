<?php
ini_set('display_errors', true);

//Initialize all settings and autoloaders
require_once(__DIR__ . "/../init.php");

$email = new \SiteMaster\Core\ExampleEmail();
$email->send();


//$result = mail('mfairchild365@gmail.com', 'the subject', 'the message');
var_dump($result);