<?php

  class Tuote extends BaseModel{

  	public $id, $myyjä_id, $kuvaus, $hinta, $lisätietoja, $lisäyspäivä;

  	public function __construct($attributes){
  		parent::__construct($attributes);
      $this->validators = array('validate_kuvaus', 'validate_hinta', 'validate_lisätiedot');
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

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO Tuote (myyjä_id, kuvaus, hinta, lisätietoja, lisäyspäivä) VALUES (:myyjaid, :kuvaus, :hinta, :lisatietoja, :lisayspaiva) RETURNING id');

      $hinta = str_replace(',','.', $this->hinta);

      $this->hinta = $hinta;

      $query->execute(array('myyjaid' => $this->myyjä_id, 'kuvaus' => $this->kuvaus, 'hinta' => floatval($this->hinta), 'lisatietoja' => $this->lisätietoja, 'lisayspaiva' => $this->lisäyspäivä));

      $rivi = $query->fetch();

      $this->id = $rivi['id'];
    }

    public function validate_kuvaus(){
      $errors = array();

      $kuvaus = str_replace(' ', '', $this->kuvaus);

      if($kuvaus == '' || $kuvaus == null || strlen($kuvaus) < 3){
        $errors[] = 'Kuvaus pitää olla vähintään 3 merkkiä!';
      }

      if(strlen($this->kuvaus) > 30){
        $errors[] = 'Kuvaus ei saa olla yli 30 merkkiä!';
      } 

      return $errors;
    }

    public function validate_hinta(){
      $errors = array();
      if($this->hinta == '' || $this->hinta == null){
        $errors[] = 'Hinta ei voi olla tyhjä!';
      }

      if(strlen($this->hinta) > 10){
        $errors[] = 'Hinta ei voi olla yli 10 numeroa!';
      }

      $hinta = str_replace(',','.', $this->hinta);

      $this->hinta = $hinta;

      if(is_numeric($this->hinta) == false){
        $errors[] = 'Hinta täytyy olla numero tai desimaaliluku!';
      }

      return $errors;
    }

    public function validate_lisätiedot(){
      $errors = array();
      if(strlen($this->lisätietoja) > 300){
        $errors[] = 'Lisätiedot eivät voi olla yli 300 merkkiä!';
      }

      return $errors;
    }
  }