<?php

class BidController extends BaseController{

    public static function index(){
	    $user_logged_in = self::get_user_logged_in();
	    $bids = Bid::find_by_person($user_logged_in->id);
	    View::make('bid/index.html', array('bids' => $bids));
  	}

  	public static function store($id){
  		$params = $_POST;
  		$date = date('Y-m-d');

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

}