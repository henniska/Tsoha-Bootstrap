<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/game', function() {
    HelloWorldController::game_list();
  });
  $routes->get('/game/1', function() {
    HelloWorldController::game_show();
  });

  $routes->get('/login', function() {
    HelloWorldController::login();
  });
  $routes->get('/register', function() {
    HelloWorldController::register();
  });
  $routes->get('/esine', function() {
    HelloWorldController::esine_lista();
  });
  $routes->get('/esine/1', function() {
    HelloWorldController::nayta_esine();
  });
  $routes->get('/lisaaesine', function() {
    HelloWorldController::lisaa_esine();
  });
  $routes->get('/profiili', function() {
    HelloWorldController::profiili();
  });
  $routes->get('/muokkaa-profiili', function() {
    HelloWorldController::muokkaa_profiili();
  });

