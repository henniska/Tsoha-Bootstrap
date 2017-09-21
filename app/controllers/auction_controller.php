<?php

class AuctionController extends BaseController{

    public static function index(){
	    $auctions = Auction::all();
	    View::make('auction/index.html', array('auctions' => $auctions));
  	}

  	public static function store(){
  		$params = $_POST;
	    $auction = new Auction(array(
			'item_name' => $params['item_name'],
			'minimum_bid' => $params['minimum_bid'],
			'description' => $params['description'],
			// väliaikainen
			'create_date' => $params['end_date'], 
			'end_date' => $params['end_date']
	    ));

	    $auction->save();

	    Redirect::to('/esine/' . $auction->id, array('message' => 'Esineen lisääminen onnistui!'));  
    }

    public static function create(){
    	View::make('auction/newAuction.html');
    }

  	public static function show($id){
  		$auction = Auction::find($id);
    	View::make('auction/auction.html', array('auction' => $auction));
    }

}