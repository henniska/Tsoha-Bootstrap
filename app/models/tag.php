<?php

class Tag extends BaseModel{

	public $id, $tag_name;

	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public static function all(){
		$query = DB::connection()->prepare('SELECT * FROM Tag ORDER BY tag_name ASC');
		$query->execute();
		$rows = $query->fetchAll();
		$tags = array();

		foreach($rows as $row){
			$tags[] = new Tag(array(
				'id' => $row['id'],
				'tag_name' => $row['tag_name']
			));
		}
		return $tags;
	}

	public static function find($id){
		$query = DB::connection()->prepare('SELECT * FROM Tag WHERE id = :id LIMIT 1');
		$query->execute(array('id' => $id));
		$row = $query->fetch();

		if($row){
			$tag = new Tag(array(
				'id' => $row['id'],
				'tag_name' => $row['tag_name']
			));

		    return $tag;
		}
		return null;
  	}
}