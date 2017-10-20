<?php

class Auction_tag extends BaseModel{

	public $id, $tag_id, $auction_id;

	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public static function find_by_auction($auction_id){
		$query = DB::connection()->prepare('SELECT * FROM Auction_tag WHERE auction_id = :auction_id');
		$query->execute(array('auction_id' => $auction_id));
		$rows = $query->fetchAll();

		$auction_tags = array();

		foreach($rows as $row){
			$auction_tags[] = new Auction_tag(array(
				'id' => $row['id'],
				'tag_id' => $row['tag_id'],
				'auction_id' => $row['auction_id']
			));
		}
		return $auction_tags;
	}

	public function save(){
	    $query = DB::connection()->prepare('INSERT INTO Auction_tag (tag_id, auction_id) VALUES (:tag_id, :auction_id) RETURNING id');
	    $query->execute(array('tag_id' => $this->tag_id, 'auction_id' => $this->auction_id));
	    $row = $query->fetch();
	    $this->id = $row['id'];
  	}

	public function destroy(){
		$query = DB::connection()->prepare('DELETE FROM Auction_tag WHERE id = :id');
		$query->execute(array('id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}
}