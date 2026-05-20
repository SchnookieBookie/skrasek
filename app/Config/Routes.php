<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ArtistController::index');
$routes->get('artists', 'ArtistController::index');
$routes->get('artist/details/(:any)', 'ArtistController::details/$1');