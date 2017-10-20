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


  $routes->get('/esine', function() {
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
  $routes->post('/esine/:id/comment', 'check_logged_in', function($id) {
    CommentController::store($id);
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
  $routes->get('/bid/:id/edit', 'check_logged_in', function($id){
    BidController::edit($id);
  });
  $routes->post('/bid/:id/edit', 'check_logged_in', function($id){
    BidController::update($id);
  });
  $routes->post('/bid/:id/destroy', 'check_logged_in', function($id){
    BidController::destroy($id);
  });
  $routes->get('/comment', 'check_logged_in', function() {
    CommentController::index();
  });
  $routes->get('/comment/:id/edit', 'check_logged_in', function($id){
    CommentController::edit($id);
  });
  $routes->post('/comment/:id/edit', 'check_logged_in', function($id){
    CommentController::update($id);
  });
  $routes->post('/comment/:id/destroy', 'check_logged_in', function($id){
    CommentController::destroy($id);
  });
  $routes->get('/personal_auctions', 'check_logged_in', function() {
    AuctionController::personal_index();
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
  $routes->get('/profile', 'check_logged_in', function() {
    UserController::showProfile();
  });
  $routes->get('/profile/edit', 'check_logged_in', function(){
    UserController::edit();
  });
  $routes->post('/profile/edit', 'check_logged_in', function(){
    UserController::update();
  });
  $routes->post('/profile/destroy', 'check_logged_in', function(){
    UserController::destroy();
  });
  $routes->get('/register', function(){
    UserController::create();
  });
  $routes->post('/register', function(){
    UserController::store();
  });
