<?php

use bng\System\Router;

require_once('../vendor/autoload.php');

Router::dispatch();

$nomes = ['joão', 'ana', 'carlos'];
$nome = 'joão ribeiro';

printData($nomes, false);
printData($nome);
