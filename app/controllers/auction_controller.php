<?php

class AuctionController extends BaseController{

    public static function index(){
	    $auctions = Auction::all();
	    View::make('auction/index.html', array('auctions' => $auctions));
  	}

  	public static function store(){
  		$params = $_POST;
  		$date = date('Y-m-d');
	    $auction = new Auction(array(
			'item_name' => $params['item_name'],
			'minimum_bid' => $params['minimum_bid'],
			'description' => $params['description'],
			// väliaikainen
			'create_date' => $date, 
			'end_date' => $params['end_date']
	    ));

	    $errors = $auction->errors();

		if(count($errors) > 0){
			View::make('auction/newAuction.html', array('errors' => $errors));
		}else{
			$auction->save();

			Redirect::to('/esine/' . $auction->id, array('message' => 'Esineen lisääminen onnistui!'));  
		}


    }

    public static function create(){
    	View::make('auction/newAuction.html');
    }

  	public static function show($id){
  		$auction = Auction::find($id);
    	View::make('auction/auction.html', array('auction' => $auction));
    }

    public static function edit($id){
		$auction = Auction::find($id);
		View::make('auction/edit.html', array('auction' => $auction));
	}

	public static function update($id){
		$params = $_POST;
		$old_auction = Auction::find($id);
		$attributes = array(
			'id' => $id,
			'person_id' => $old_auction->person_id,
			'item_name' => $params['item_name'],
			'minimum_bid' => $params['minimum_bid'],
			'description' => $params['description'],
			'create_date' => $old_auction->create_date,
			'end_date' => $params['end_date']
		);

		$auction = new Auction($attributes);
		$errors = $auction->errors();

		if(count($errors) > 0){
			View::make('auction/edit.html', array('errors' => $errors, 'auction' => $auction));
		}else{
			$auction->update();

			Redirect::to('/esine/' . $auction->id, array('message' => 'Huutokauppaa on muokattu onnistuneesti!'));
		}
	}

	public static function destroy($id){
		$auction = new Auction(array('id' => $id));
		$auction->destroy();

		Redirect::to('/esine', array('message' => 'Huutokauppa on poistettu onnistuneesti!'));
	}

}