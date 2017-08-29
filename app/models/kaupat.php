<?php

  class Kaupat extends BaseModel{

  	public $id, $tarjous_id, $päivämäärä, $tarjous_hintatarjous, $tuote_kuvaus, $tarjous_lisätietoja, $tarjous_tuote_id, $tuote_myyjä_nimi;

  	public function __construct($attributes){
  	  parent::__construct($attributes);
  	}

  	public function tallenna(){
  	  $query = DB::connection()->prepare('INSERT INTO Kaupat (tarjous_id) VALUES (:tarjous_id) RETURNING id');
  	  $query->execute(array('tarjous_id' => $this->tarjous_id));

  	  $rivi = $query->fetch();

  	  $this->id = $rivi['id'];
  	}

  	public static function käyttäjän_ostamat_tuotteet($id){
  	  $query = DB::connection()->prepare('SELECT ta.hintatarjous, tu.kuvaus, k.päivämäärä, ta.lisätietoja, ta.tuote_id FROM Kaupat k INNER JOIN Tarjous ta ON k.tarjous_id = ta.id INNER JOIN Tuote tu ON ta.tuote_id = tu.id WHERE ta.ostaja_id = :id');
  	  $query->execute(array('id' => $id));

  	  $rivit = $query->fetchAll();
  	  $kaupat = array();

  	  foreach($rivit as $rivi){
  		$kaupat[] = new Kaupat(array(
  	 	  'tarjous_hintatarjous' => $rivi['hintatarjous'],
  	 	  'tuote_kuvaus' => $rivi['kuvaus'],
  	 	  'päivämäärä' => $rivi['päivämäärä'],
  	 	  'tarjous_lisätietoja' => $rivi['lisätietoja'],
  	 	  'tarjous_tuote_id' => $rivi['tuote_id']
  		  ));
  	  }

  	  return $kaupat;
  	}

  	public static function käyttäjän_myydyt_tuotteet($id){
  	  $query = DB::connection()->prepare('SELECT ta.hintatarjous, tu.kuvaus, k.päivämäärä, ta.lisätietoja, ta.tuote_id, kä.nimi FROM Kaupat k INNER JOIN Tarjous ta ON k.tarjous_id = ta.id INNER JOIN Tuote tu ON ta.tuote_id = tu.id INNER JOIN Käyttäjä kä ON tu.myyjä_id = kä.id WHERE tu.myyjä_id = :id');
  	  $query->execute(array('id' => $id));

  	  $rivit = $query->fetchAll();
  	  $kaupat = array();

  	  foreach($rivit as $rivi){
  	  	$kaupat[] = new Kaupat(array(
  	  	  'tarjous_hintatarjous' => $rivi['hintatarjous'],
  	 	  'tuote_kuvaus' => $rivi['kuvaus'],
  	 	  'päivämäärä' => $rivi['päivämäärä'],
  	 	  'tarjous_lisätietoja' => $rivi['lisätietoja'],
  	 	  'tarjous_tuote_id' => $rivi['tuote_id'],
  	 	  'tuote_myyjä_nimi' => $rivi['nimi']
  	  	  ));
  	  }

  	  return $kaupat;
  	}

    public static function onko_tarjous_hyväksytty($id){
      $query = DB::connection()->prepare('SELECT * FROM Kaupat WHERE tarjous_id = :id');
      $query->execute(array('id' => $id));

      $rivi = $query->fetchAll();

      if($rivi){
        return true;
      } else {
        return false;
      }
    }
  }