<?php

  class Tuote extends BaseModel{

  	public $id, $myyjä_id, $kuvaus, $hinta, $lisätietoja, $lisäyspäivä;

  	public function __construct($attributes){
  		parent::__construct($attributes);
  	}

  	public static function all(){
  		$query = DB::connection()->prepare('SELECT * FROM Tuote');
  		$query->execute();
  		$rivit = $query->fetchAll();
  		$tuotteet = array();

  		foreach($rivit as $rivi){
  			$tuotteet[] = new Tuote(array(
  				'id' => $rivi['id'],
  				'myyjä_id' => $rivi['myyjä_id'],
  				'kuvaus' => $rivi['kuvaus'],
  				'hinta' => $rivi['hinta'],
  				'lisätietoja' => $rivi['lisätietoja'],
  				'lisäyspäivä' => $rivi['lisäyspäivä']
  				));
  		}

  		return $tuotteet;
  	}

  	public static function find($id){
  		$query = DB::connection()->prepare('SELECT * FROM Tuote WHERE id = :id LIMIT 1');
  		$query->execute(array('id' => $id));
  		$rivi = $query->fetch();

  		if ($rivi){
  			$tuote = new Tuote(array(
  				'id' => $rivi['id'],
  				'myyjä_id' => $rivi['myyjä_id'],
  				'kuvaus' => $rivi['kuvaus'],
  				'hinta' => $rivi['hinta'],
  				'lisätietoja' => $rivi['lisätietoja'],
  				'lisäyspäivä' => $rivi['lisäyspäivä']
  				));

  			return $tuote;
  		}
  	}
  }