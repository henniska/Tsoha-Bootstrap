<?php

class Bid extends BaseModel{

	public $id, $person_id, $auction_id, $money_value, $create_date;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validate_money_value', 'validate_create_date', 'validate_person_id');
	}

	public static function find_by_person($id){
		$query = DB::connection()->prepare('SELECT * FROM Bid WHERE person_id = :person_id');
		$query->execute(array('person_id' => $id));
		$rows = $query->fetchAll();
		$bids = array();

		foreach($rows as $row){
			$bids[] = new Bid(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'money_value' => $row['money_value'],
				'create_date' => $row['create_date']
			));
		}
		return $bids;
	}

	public static function find_by_auction($auction_id){
		$query = DB::connection()->prepare('SELECT * FROM Bid WHERE auction_id = :auction_id');
		$query->execute(array('auction_id' => $auction_id));
		$rows = $query->fetchAll();

		$bids = array();

		foreach($rows as $row){
			$bids[] = new Bid(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'money_value' => $row['money_value'],
				'create_date' => $row['create_date']
			));
		}
		return $bids;
	}

	public static function largest_by_auction($id){
		$bids = self::find_by_auction($id);

		if (empty($bids)) {
		    return null;
		}

		$largest_bid = null;
		foreach ($bids as $bid) {
			if ($largest_bid == null) {
				$largest_bid = $bid;
			}
			if ($largest_bid->money_value < $bid->money_value) {
				$largest_bid = $bid;
			}
		}
		return $largest_bid; 
	}

	public static function find($id){
		$query = DB::connection()->prepare('SELECT * FROM Bid WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if($row){
			$bid = new Bid(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'money_value' => $row['money_value'],
				'create_date' => $row['create_date']
			));

		    return $bid;
		}
		return null;
  	}

  	public function save(){
	    $query = DB::connection()->prepare('INSERT INTO Bid (person_id, auction_id, money_value, create_date) VALUES (:person_id, :auction_id, :money_value, :create_date) RETURNING id');
	    $query->execute(array('person_id' => $this->person_id, 'auction_id' => $this->auction_id, 'money_value' => $this->money_value, 'create_date' => $this->create_date));
	    $row = $query->fetch();
	    $this->id = $row['id'];
  	}

  	public function update(){
		$query = DB::connection()->prepare('UPDATE Bid SET (money_value) = (:money_value) WHERE id = :id');
		$query->execute(array('money_value' => $this->money_value, 'id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	public function destroy(){
		$query = DB::connection()->prepare('DELETE FROM Bid WHERE id = :id');
		$query->execute(array('id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

  	//Validaattorit

  	public function validate_money_value(){
		$errors = array();
		if($this->money_value == '' || $this->money_value == null){
			$errors[] = 'Tarjous ei saa olla tyhjä!';
		}
		if(is_numeric($this->money_value)){
			if($this->money_value < 0){
				$errors[] = 'Tarjous pitää olla suurempi kuin nolla!';
			}

		} else {
			$errors[] = 'Tarjouksen pitää olla numero!';
		}

		$auction = Auction::find($this->auction_id);

		if ($auction->minimum_bid > $this->money_value) {
			$errors[] = 'Tarjouksen pitää olla suurempi kuin alkuhinta!';
		}

		$bids = self::find_by_auction($this->auction_id);

		foreach ($bids as $bid) {
			if ($bid->money_value >= $this->money_value) {
				$errors[] = 'Tarjouksen pitää olla suurin tarjous!';
				break;
			}
		} 

		return $errors;
	}

	public function validate_create_date(){
		$errors = array();
		$auction = Auction::find($this->auction_id);

		if ($this->create_date > $auction->end_date) {
			$errors[] = 'Huutokauppa on loppunut!';
		}

		return $errors;
	}

	public function validate_person_id(){
		$errors = array();
		$auction = Auction::find($this->auction_id);

		if ($this->person_id == $auction->person_id) {
			$errors[] = 'Et saa antaa tarjousta omalle esineelle!';
		}

		return $errors;
	}
}