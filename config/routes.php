<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });


  $routes->get('/esine', function() {
    AuctionController::index();
  });
  $routes->post('/esine', function() {
    AuctionController::store();
  });
  $routes->get('/esine/uusi', function() {
    AuctionController::create();
  });
  $routes->get('/esine/:id', function($id) {
    AuctionController::show($id);
  });
  $routes->get('/esine/:id/edit', function($id){
    AuctionController::edit($id);
  });
  $routes->post('/esine/:id/edit', function($id){
    AuctionController::update($id);
  });
  $routes->post('/esine/:id/destroy', function($id){
    AuctionController::destroy($id);
  });


  $routes->get('/profiili', function() {
    HelloWorldController::profiili();
  });
  $routes->get('/muokkaa-profiili', function() {
    HelloWorldController::muokkaa_profiili();
  });
  $routes->get('/login', function() {
    HelloWorldController::login();
  });
  $routes->get('/register', function() {
    HelloWorldController::register();
  });
