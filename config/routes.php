<?php

  function check_logged_in(){
    BaseController::check_logged_in();
  }

  $routes->get('/', function() {
    HelloWorldController::index();
  });
  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });


  $routes->get('/esine', 'check_logged_in', function() {
    AuctionController::index();
  });
  $routes->post('/esine', 'check_logged_in', function() {
    AuctionController::store();
  });
  $routes->get('/esine/uusi', 'check_logged_in', function() {
    AuctionController::create();
  });
  $routes->get('/esine/:id', 'check_logged_in', function($id) {
    AuctionController::show($id);
  });
  $routes->post('/esine/:id/bid', 'check_logged_in', function($id) {
    BidController::store($id);
  });
  $routes->get('/esine/:id/edit', 'check_logged_in', function($id){
    AuctionController::edit($id);
  });
  $routes->post('/esine/:id/edit', 'check_logged_in', function($id){
    AuctionController::update($id);
  });
  $routes->post('/esine/:id/destroy', 'check_logged_in', function($id){
    AuctionController::destroy($id);
  });
  $routes->get('/bid', 'check_logged_in', function() {
    BidController::index();
  });
  $routes->get('/login', function(){
    UserController::login();
  });
  $routes->post('/login', function(){
    UserController::handle_login();
  });
  $routes->post('/logout', function(){
    UserController::logout();
  });


  $routes->get('/profiili', function() {
    HelloWorldController::profiili();
  });
  $routes->get('/muokkaa-profiili', function() {
    HelloWorldController::muokkaa_profiili();
  });
  $routes->get('/register', function() {
    HelloWorldController::register();
  });
