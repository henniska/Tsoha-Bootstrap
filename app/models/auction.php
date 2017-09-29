<?php

class Auction extends BaseModel{

	public $id, $person_id, $item_name, $minimum_bid, $description, $create_date, $end_date;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validate_item_name', 'validate_minimum_bid', 'validate_end_date');
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

  	public function update(){
		$query = DB::connection()->prepare('UPDATE Auction SET (item_name, minimum_bid, description, create_date, end_date) = (:item_name, :minimum_bid, :description, :create_date, :end_date) WHERE id = :id');
		$query->execute(array('item_name' => $this->item_name, 'minimum_bid' => $this->minimum_bid, 'description' => $this->description, 'create_date' => $this->create_date, 'end_date' => $this->end_date, 'id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	public function destroy(){
		$query = DB::connection()->prepare('DELETE FROM Auction WHERE id = :id');
		$query->execute(array('id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	//Validaattorit

	public function validate_item_name(){
		$errors = array();
		if($this->item_name == '' || $this->item_name == null){
			$errors[] = 'Nimi ei saa olla tyhjä!';
		}
		if(strlen($this->item_name) < 3){
			$errors[] = 'Nimen pituuden tulee olla vähintään kolme merkkiä!';
		}

		return $errors;
	}

	public function validate_minimum_bid(){
		$errors = array();
		if($this->minimum_bid == '' || $this->minimum_bid == null){
			$errors[] = 'Alkuhinta ei saa olla tyhjä!';
		}
		if(is_numeric($this->minimum_bid)){
			if($this->minimum_bid < 0){
				$errors[] = 'Alkuhinta pitää olla suurempi kuin nolla!';
			}

		} else {
			$errors[] = 'Alkuhinta pitää olla numero!';
		}

		return $errors;
	}

	public function validate_end_date(){
		$errors = array();
		if($this->end_date == '' || $this->end_date == null){
			$errors[] = 'Loppumisaika ei saa olla tyhjä!';
		}
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->end_date)) {
			if ($this->create_date > $this->end_date) {
				$errors[] = 'Loppumisaika on ennen huutokaupan alkua!';
			}
		} else {
			$errors[] = 'Loppumisaika ei ole formaatissa YYYY-MM-DD!';
		}

		return $errors;
	}
}