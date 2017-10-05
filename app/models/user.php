<?php

class User extends BaseModel{

	public $id, $username, $name, $email, $phone, $home_address, $password;

	public function __construct($attributes){
		parent::__construct($attributes);
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
}