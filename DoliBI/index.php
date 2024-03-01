<?php
const PREFIX_TO_RELATIVE_PATH = "/DoliBI";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\Router;

$router = new Router(new DefaultComponentFactory()) ;
$router->route(PREFIX_TO_RELATIVE_PATH);
