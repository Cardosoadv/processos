<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/processoobjeto/salvar', 'ProcessoObjeto::salvar');

service('auth')->routes($routes);
