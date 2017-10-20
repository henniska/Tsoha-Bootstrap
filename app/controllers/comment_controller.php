<?php

class CommentController extends BaseController{

    public static function index(){
	    $user_logged_in = self::get_user_logged_in();
	    $comments = Comment::find_by_person($user_logged_in->id);

	    //comment_auction avulla saadaan kommenttiin liittyvä huutokauppa.
	    $comment_auctions = array();
	    foreach ($comments as $comment) {
	    	$comment_auctions[$comment->id] = Auction::find($comment->auction_id);
	    }

	    View::make('comment/index.html', array('comments' => $comments, 'comment_auctions' => $comment_auctions, 'user' => $user_logged_in));
  	}

  	public static function store($id){
  		$params = $_POST;
  		$date = date('Y-m-d H:i:s');

  		$user_logged_in = self::get_user_logged_in();

	    $comment = new Comment(array(
			'person_id' => $user_logged_in->id,
			'auction_id' => $id,
			'description' => $params['description'],
			'create_date' => $date
	    ));

	    $errors = $comment->errors();

		if(count($errors) > 0){

			Redirect::to('/esine/' . $id, array('errors' => $errors));
		}else{
			$comment->save();

			Redirect::to('/esine/' . $id, array('message' => 'Kommentin lisääminen onnistui!'));  
		}

    }

    public static function edit($id){
		$comment = Comment::find($id);
		View::make('comment/edit.html', array('comment' => $comment));
	}

	public static function update($id){
		$params = $_POST;
		$old_comment = Comment::find($id);
		$attributes = array(
			'id' => $id,
			'person_id' => $old_comment->person_id,
			'auction_id' => $old_comment->auction_id,
			'description' => $params['description'],
			'create_date' => $old_comment->create_date
		);

		$comment = new Comment($attributes);
		$errors = $comment->errors();

		if(count($errors) > 0){
			View::make('comment/edit.html', array('errors' => $errors, 'comment' => $comment));
		}else{
			$comment->update();

			Redirect::to('/comment', array('message' => 'Kommenttia on muokattu onnistuneesti!'));
		}
	}

	public static function destroy($id){
		$comment = new Comment(array('id' => $id));
		$comment->destroy();

		Redirect::to('/comment', array('message' => 'Kommentti on poistettu onnistuneesti!'));
	}

}