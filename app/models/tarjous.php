<?php

  class Tarjous extends BaseModel{

  	public $id, $tuote_id, $ostaja_id, $myyjä_nimi, $ostaja_nimi, $tuote_kuvaus, $hintatarjous, $lisätietoja, $päivämäärä;

  	public function __construct($attributes){
  		parent::__construct($attributes);
  		//validaattorit
  	}

  	public function tallenna(){
  		$query = DB::connection()->prepare('INSERT INTO Tarjous (tuote_id, ostaja_id, hintatarjous, lisätietoja) VALUES (:tuote_id, :ostaja_id, :hintatarjous, :lisatietoja) RETURNING id');

  		$hinta = str_replace(',','.', $this->hintatarjous);

      	$this->hintatarjous = $hinta;

  		$query->execute(array('tuote_id' => $this->tuote_id, 'ostaja_id' => $this->ostaja_id, 'hintatarjous' => $this->hintatarjous, 'lisatietoja' => $this->lisätietoja));

  		$rivi = $query->fetch();

  		$this->id = $rivi['id'];
  	}

  	public static function käyttäjän_tekemät_tarjoukset($id){
  		$query = DB::connection()->prepare('SELECT ta.id, ta.tuote_id, ta.ostaja_id, tu.kuvaus, ta.hintatarjous, ta.lisätietoja, ta.päivämäärä FROM Tarjous ta INNER JOIN Tuote tu ON ta.tuote_id = tu.id WHERE ostaja_id = :id');
  		$query->execute(array('id' => $id));

  		$rivit = $query->fetchAll();
  		$tarjoukset = array();

  		foreach($rivit as $rivi){
  			$tarjoukset[] = new Tarjous(array(
  				'id' => $rivi['id'],
  				'tuote_id' => $rivi['tuote_id'],
  				'ostaja_id' => $rivi['ostaja_id'],
  				'tuote_kuvaus' => $rivi['kuvaus'],
  				'hintatarjous' => $rivi['hintatarjous'],
  				'lisätietoja' => $rivi['lisätietoja'],
  				'päivämäärä' => $rivi['päivämäärä']
  				));
  		}

  		return $tarjoukset;
  	}

  	public static function käyttäjälle_tehdyt_tarjoukset($id){
  		$query = DB::connection()->prepare('SELECT ta.id, ta.tuote_id, ta.ostaja_id, tu.kuvaus, ta.hintatarjous, ta.lisätietoja, ta.päivämäärä, k.nimi FROM Tarjous ta INNER JOIN Tuote tu ON ta.tuote_id = tu.id INNER JOIN Käyttäjä k ON k.id = tu.myyjä_id WHERE tu.myyjä_id = :id');
  		$query->execute(array('id' => $id));

  		$rivit = $query->fetchAll();
  		$tarjoukset = array();

  		foreach($rivit as $rivi){
  			$tarjoukset[] = new Tarjous(array(
  				'id' => $rivi['id'],
  				'tuote_id' => $rivi['tuote_id'],
  				'ostaja_id' => $rivi['ostaja_id'],
  				'ostaja_nimi' => $rivi['nimi'],
  				'tuote_kuvaus' => $rivi['kuvaus'],
  				'hintatarjous' => $rivi['hintatarjous'],
  				'lisätietoja' => $rivi['lisätietoja'],
  				'päivämäärä' => $rivi['päivämäärä']
  				));
  		}

  		return $tarjoukset;
  	}


  }