<?php

class Comment extends BaseModel{

	public $id, $person_id, $auction_id, $description, $create_date;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validate_description');
	}

	public static function find_by_person($id){
		$query = DB::connection()->prepare('SELECT * FROM Comment WHERE person_id = :person_id ORDER BY create_date DESC');
		$query->execute(array('person_id' => $id));
		$rows = $query->fetchAll();
		$comments = array();

		foreach($rows as $row){
			$comments[] = new Comment(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'description' => $row['description'],
				'create_date' => $row['create_date']
			));
		}
		return $comments;
	}

	public static function find_by_auction($auction_id){
		$query = DB::connection()->prepare('SELECT * FROM Comment WHERE auction_id = :auction_id ORDER BY create_date DESC');
		$query->execute(array('auction_id' => $auction_id));
		$rows = $query->fetchAll();

		$comments = array();

		foreach($rows as $row){
			$comments[] = new Comment(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'description' => $row['description'],
				'create_date' => $row['create_date']
			));
		}
		return $comments;
	}

	public static function find($id){
		$query = DB::connection()->prepare('SELECT * FROM Comment WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if($row){
			$comment = new Comment(array(
				'id' => $row['id'],
				'person_id' => $row['person_id'],
				'auction_id' => $row['auction_id'],
				'description' => $row['description'],
				'create_date' => $row['create_date']
			));

		    return $comment;
		}
		return null;
  	}

  	public function save(){
	    $query = DB::connection()->prepare('INSERT INTO Comment (person_id, auction_id, description, create_date) VALUES (:person_id, :auction_id, :description, :create_date) RETURNING id');
	    $query->execute(array('person_id' => $this->person_id, 'auction_id' => $this->auction_id, 'description' => $this->description, 'create_date' => $this->create_date));
	    $row = $query->fetch();
	    $this->id = $row['id'];
  	}

  	public function update(){
		$query = DB::connection()->prepare('UPDATE Comment SET (description) = (:description) WHERE id = :id');
		$query->execute(array('description' => $this->description, 'id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	public function destroy(){
		$query = DB::connection()->prepare('DELETE FROM Comment WHERE id = :id');
		$query->execute(array('id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}


  	//Validaattorit

	public function validate_description(){
		$errors = array();
		if($this->description == '' || $this->description == null){
			$errors[] = 'Kommentti ei saa olla tyhjä!';
		}
		if(strlen($this->description) > 2000){
			$errors[] = 'Kommentti on liian pitkä!';
		}

		return $errors;
	}
}