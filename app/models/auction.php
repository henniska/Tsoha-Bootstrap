<?php

class Auction extends BaseModel{

	public $id, $person_id, $item_name, $minimum_bid, $description, $create_date, $end_date;

	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public static function all(){
		$query = DB::connection()->prepare('SELECT * FROM Auction');
		$query->execute();
		$rows = $query->fetchAll();
		$auctions = array();

		foreach($rows as $row){
			$auctions[] = new Auction(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'item_name' => $row['item_name'],
				'minimum_bid' => $row['minimum_bid'],
				'description' => $row['description'],
				'create_date' => $row['create_date'],
				'end_date' => $row['end_date']
			));
		}
		return $auctions;
	}

	public static function find($id){
		$query = DB::connection()->prepare('SELECT * FROM Auction WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if($row){
			$auction = new Auction(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'item_name' => $row['item_name'],
				'minimum_bid' => $row['minimum_bid'],
				'description' => $row['description'],
				'create_date' => $row['create_date'],
				'end_date' => $row['end_date']
			));

		    return $auction;
		}
		return null;
  	}

  	public function save(){
	    $query = DB::connection()->prepare('INSERT INTO Auction (item_name, minimum_bid, description, create_date, end_date) VALUES (:item_name, :minimum_bid, :description, :create_date, :end_date) RETURNING id');
	    $query->execute(array('item_name' => $this->item_name, 'minimum_bid' => $this->minimum_bid, 'description' => $this->description, 'create_date' => $this->create_date, 'end_date' => $this->end_date));
	    $row = $query->fetch();
	    $this->id = $row['id'];
  	}
}