<?php

class User extends BaseModel{

	public $id, $username, $name, $email, $phone, $home_address, $password;

	public function __construct($attributes){
		parent::__construct($attributes);
		$this->validators = array('validate_username');
	}

	public static function authenticate($username, $password){
		$query = DB::connection()->prepare('SELECT * FROM Person WHERE username = :username AND password = :password LIMIT 1');
		$query->execute(array('username' => $username, 'password' => $password));
		$row = $query->fetch();

		if($row){
			$user = new User(array(
				'id' => $row['id'],
				'username' => $row['username'],
				'name' => $row['name'],
				'email' => $row['email'],
				'phone' => $row['phone'],
				'home_address' => $row['home_address'],
				'password' => $row['password']
			));

			return $user;
		}
		return null;
	}

	public static function find($id){
		$query = DB::connection()->prepare('SELECT * FROM Person WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if($row){
			$user = new User(array(
				'id' => $row['id'],
				'username' => $row['username'],
				'name' => $row['name'],
				'email' => $row['email'],
				'phone' => $row['phone'],
				'home_address' => $row['home_address'],
				'password' => $row['password']
			));

		    return $user;
		}
		return null;
  	}

  	public function save(){
	    $query = DB::connection()->prepare('INSERT INTO Person (username, name, email, phone, home_address, password) VALUES (:username, :name, :email, :phone, :home_address, :password) RETURNING id');
	    $query->execute(array('username' => $this->username, 'name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'home_address' => $this->home_address, 'password' => $this->password));
	    $row = $query->fetch();
	    $this->id = $row['id'];
  	}

  	public function update(){
		$query = DB::connection()->prepare('UPDATE Person SET (username, name, email, phone, home_address, password) = (:username, :name, :email, :phone, :home_address, :password) WHERE id = :id');
		$query->execute(array('username' => $this->username, 'name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'home_address' => $this->home_address, 'password' => $this->password, 'id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	public function destroy(){
		$query = DB::connection()->prepare('DELETE FROM Person WHERE id = :id');
		$query->execute(array('id' => $this->id));
		// $row = $query->fetch();

		// Kint::dump($row);
	}

	//Validaattorit

	public function validate_username(){
		$errors = array();
		if($this->username == '' || $this->username == null){
			$errors[] = 'Käyttäjänimi ei saa olla tyhjä!';
		}
		if(strlen($this->username) < 3){
			$errors[] = 'Käyttäjänimen pituuden tulee olla vähintään kolme merkkiä!';
		}

		return $errors;
	}
}