<?php
const PREFIX_TO_RELATIVE_PATH = "/DoliBI";

use application\DefaultComponentFactory;

use yasmf\Router;


$router = new Router(new DefaultComponentFactory()) ;
$router->route(PREFIX_TO_RELATIVE_PATH);