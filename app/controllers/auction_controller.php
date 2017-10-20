<?php

class AuctionController extends BaseController{

    public static function index(){
	    $auctions = Auction::all();
	    $bids = array();
	    foreach ($auctions as $auction) {
	    	$bids[$auction->id] = Bid::largest_by_auction($auction->id);
	    }
	    View::make('auction/index.html', array('auctions' => $auctions, 'bids' => $bids));
  	}

  	public static function personal_index(){
  		$user_logged_in = self::get_user_logged_in();

  		//Nämä ovat käyttäjän luomia huutokauppoja
	    $auctions = Auction::find_by_person($user_logged_in->id);
	    $bids = array();
	    foreach ($auctions as $auction) {
	    	$bids[$auction->id] = Bid::largest_by_auction($auction->id);
	    }

	    //Nämä ovat käyttäjän myytyjä huutokauppoja/esineitä.
	    $sell_auctions = Auction::find_by_seller_completed($user_logged_in->id);
	    $sell_bids = array();
	    foreach ($sell_auctions as $auction) {
	    	$sell_bids[$auction->id] = Bid::largest_by_auction($auction->id);
	    }
	    $buyers = array();
	    foreach ($sell_auctions as $auction) {
	    	$buyers[$auction->id] = User::find($sell_bids[$auction->id]->person_id);
	    }

	    //Nämä ovat käyttäjän ostettuja esineitä.
	    $buy_auctions = Auction::find_by_buyer_completed($user_logged_in->id);
	    $buy_bids = array();
	    foreach ($buy_auctions as $auction) {
	    	$buy_bids[$auction->id] = Bid::largest_by_auction($auction->id);
	    }
	    $sellers = array();
	    foreach ($buy_auctions as $auction) {
	    	$sellers[$auction->id] = User::find($auction->person_id);
	    }

	    View::make('auction/personal_index.html', array('auctions' => $auctions, 'bids' => $bids, 'sell_auctions' => $sell_auctions, 'sell_bids' => $sell_bids, 'buyers' => $buyers, 'buy_auctions' => $buy_auctions, 'buy_bids' => $buy_bids, 'sellers' => $sellers));
  	}

  	public static function store(){
  		$params = $_POST;
  		$date = date('Y-m-d H:i:s');
  		$user_logged_in = self::get_user_logged_in();

	    $auction = new Auction(array(
	    	'person_id' => $user_logged_in->id,
			'item_name' => $params['item_name'],
			'minimum_bid' => $params['minimum_bid'],
			'description' => $params['description'],
			'create_date' => $date, 
			'end_date' => $params['end_date']
	    ));

	    $errors = $auction->errors();

		if(count($errors) > 0){
			$tags = Tag::all();
			View::make('auction/newAuction.html', array('errors' => $errors, 'tags' => $tags));
		}else{
			$auction->save();

			if (isset($params['selected_tags'])) {
				$tag_ids = $params['selected_tags'];
			    foreach ($tag_ids as $tag_id) {
			    	$auction_tag = new Auction_tag(array(
				    	'tag_id' => $tag_id,
						'auction_id' => $auction->id
				    ));
				    $auction_tag->save();
			    }
			}

			Redirect::to('/esine/' . $auction->id, array('message' => 'Esineen lisääminen onnistui!'));  
		}


    }

    public static function create(){
    	$tags = Tag::all();
    	View::make('auction/newAuction.html', array('tags' => $tags));
    }

  	public static function show($id){
  		$auction = Auction::find($id);
  		$bid = Bid::largest_by_auction($id);
  		$auction_tags = Auction_tag::find_by_auction($id);
  		$tags = array();
	    foreach ($auction_tags as $auction_tag) {
	    	$tags[] = Tag::find($auction_tag->tag_id);
	    }

  		$comments = Comment::find_by_auction($id);
  		$comment_owners = array();
	    foreach ($comments as $comment) {
	    	$comment_owners[$comment->id] = User::find($comment->person_id);
	    }

	    if ($auction->person_id == self::get_user_logged_in()->id) {
	    	$show_buttons = True;
	    } else {
	    	$show_buttons = False;
	    }
  		
  		if ($bid == null) {
  			View::make('auction/auction.html', array('auction' => $auction, 'tags' => $tags, 'comments' => $comments, 'comment_owners' => $comment_owners, 'show_buttons' => $show_buttons));
  		} else {
  			$bid_owner = User::find($bid->person_id);
  			View::make('auction/auction.html', array('auction' => $auction, '$tags' => $tags, 'comments' => $comments, 'bid' => $bid, 'bid_owner' => $bid_owner, 'comment_owners' => $comment_owners, 'show_buttons' => $show_buttons));
  		}
    }

    public static function edit($id){
		$auction = Auction::find($id);
		$tags = Tag::all();

		View::make('auction/edit.html', array('auction' => $auction, 'tags' => $tags));
	}

	public static function update($id){

		//Tätä validointia ei voi käyttää huutokaupan luomisessa, koska id:ttä ei ole vielä luotu.
		//Siksi en lisännyt sitä luokan normaaleihin validaattoreihin.
		$bids = Bid::find_by_auction($id);
		if (count($bids) > 0) {
			$errors = array();
			$errors[] = 'Et voi enää muokata, koska tarjouksia on annettu';
			View::make('auction/edit.html', array('errors' => $errors));

		} else {

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
				$tags = Tag::all();
				View::make('auction/edit.html', array('errors' => $errors, 'auction' => $auction, 'tags' => $tags));
			}else{
				$auction->update();

				$auction_tags = Auction_tag::find_by_auction($id);
				foreach ($auction_tags as $auction_tag) {
			    	$auction_tag->destroy();
			    }

			    if (isset($params['selected_tags'])) {
			    	$tag_ids = $params['selected_tags'];
				    foreach ($tag_ids as $tag_id) {
				    	$auction_tag = new Auction_tag(array(
					    	'tag_id' => $tag_id,
							'auction_id' => $id
					    ));
					    $auction_tag->save();
				    }
			    }

				Redirect::to('/esine/' . $auction->id, array('message' => 'Huutokauppaa on muokattu onnistuneesti!'));
			}
		}
	}

	public static function destroy($id){
		$auction = Auction::find($id);

		//Vain huutokaupan luoja voi poistaa sen.
		$errors = $auction->validate_person_id();

		if (count($errors) > 0) {
			Redirect::to('/esine');
		} else {
			$auction->destroy();

			Redirect::to('/esine', array('message' => 'Huutokauppa on poistettu onnistuneesti!'));
		}
	}

}