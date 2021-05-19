<?php

$pathFilebird       = '../public/configbird.json';
$configJsonbird     = file_get_contents($pathFilebird);
$installConfigbird  = json_decode($configJsonbird);

define('HOSTS',''.$installConfigbird->firebird->hostbird.'');

define('USERS',''.$installConfigbird->firebird->userbird.'');

define('SENHAS',''.$installConfigbird->firebird->senhabird.'');

define('BDS','');

?>