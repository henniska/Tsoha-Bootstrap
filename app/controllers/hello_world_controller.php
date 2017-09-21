<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      //echo 'Hello World!';
      //View::make('helloworld.html');
      $ps3 = Auction::find(1);
      $auctions = Auction::all();
      Kint::dump($ps3);
      Kint::dump($auctions);
    }

    public static function game_list(){
      View::make('suunnitelmat/game_list.html');
    }

    public static function game_show(){
      View::make('suunnitelmat/game_show.html');
    }

    public static function login(){
      View::make('suunnitelmat/login.html');
    }

    public static function register(){
      View::make('suunnitelmat/register.html');
    }

    public static function profiili(){
      View::make('suunnitelmat/profile.html');
    }

    public static function esine_lista(){
      View::make('suunnitelmat/auctionList.html');
    }

    public static function muokkaa_profiili(){
      View::make('suunnitelmat/editProfile.html');
    }

    public static function nayta_esine(){
      View::make('suunnitelmat/auction.html');
    }

    public static function lisaa_esine(){
      View::make('suunnitelmat/addAuction.html');
    }
  }
