<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/musicsick', 'MusicController::musick');
$routes->get('/uploadSongs', 'SongUploadController::index');
$routes->get('/searchSong', 'MusicController::searchSong');
$routes->get('/playlist/(:any)', 'MusicController::playlist/$1');
$routes->get('/deleteFromPlaylist/(:any)', 'MusicController::deleteFromPlaylist/$1');
$routes->post('saveSong', 'SongUploadController::upload');
$routes->post('createPlaylist', 'MusicController::createPlaylist');
$routes->post('addToPlaylist', 'MusicController::addToPlaylist');