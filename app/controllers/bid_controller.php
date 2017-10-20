<?php

class BidController extends BaseController{

    public static function index(){
	    $user_logged_in = self::get_user_logged_in();
	    $bids = Bid::find_by_person($user_logged_in->id);

	    $bid_auctions = array();
	    foreach ($bids as $bid) {
	    	$bid_auctions[$bid->id] = Auction::find($bid->auction_id);
	    }

	    View::make('bid/index.html', array('bids' => $bids, 'bid_auctions' => $bid_auctions));
  	}

  	public static function store($id){
  		$params = $_POST;
  		$date = date('Y-m-d H:i:s');

  		$user_logged_in = self::get_user_logged_in();

	    $bid = new Bid(array(
			'person_id' => $user_logged_in->id,
			'auction_id' => $id,
			'money_value' => $params['money_value'],
			'create_date' => $date
	    ));

	    $errors = $bid->errors();

		if(count($errors) > 0){

			Redirect::to('/esine/' . $id, array('errors' => $errors));
		}else{
			$bid->save();

			Redirect::to('/esine/' . $id, array('message' => 'Tarjouksen lisääminen onnistui!'));  
		}

    }

     public static function edit($id){
		$bid = Bid::find($id);
		View::make('bid/edit.html', array('bid' => $bid));
	}

	public static function update($id){
		$params = $_POST;
		$old_bid = Bid::find($id);
		$date = date('Y-m-d H:i:s');

		$attributes = array(
			'id' => $id,
			'person_id' => $old_bid->person_id,
			'auction_id' => $old_bid->auction_id,
			'money_value' => $params['money_value'],
			'create_date' => $date
		);

		$bid = new Bid($attributes);
		$errors = $bid->errors();

		if(count($errors) > 0){
			View::make('bid/edit.html', array('errors' => $errors, 'bid' => $bid));
		}else{
			$bid->update();

			Redirect::to('/bid', array('message' => 'Tarjousta on muokattu onnistuneesti!'));
		}
	}

	public static function destroy($id){
		$bid = Bid::find($id);
		$bid->destroy();

		Redirect::to('/bid', array('message' => 'Tarjous on poistettu onnistuneesti!'));
	}

}