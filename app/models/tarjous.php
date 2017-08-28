<?php

  class Tarjous extends BaseModel{

  	public $id, $tuote_id, $ostaja_id, $myyjä_nimi, $ostaja_nimi, $tuote_kuvaus, $hintatarjous, $lisätietoja, $päivämäärä;

  	public function __construct($attributes){
  		parent::__construct($attributes);
  		$this->validators = array('validate_hintatarjous', 'validate_lisätiedot');
  	}

    public static function etsi($id){
      $query = DB::connection()->prepare('SELECT * FROM Tarjous WHERE id = :id LIMIT 1');
      $query->execute(array('id' => $id));
      $rivi = $query->fetch();

      if ($rivi){
        $tarjous = new Tarjous(array(
          'id' => $rivi['id'],
          'tuote_id' => $rivi['tuote_id'],
          'ostaja_id' => $rivi['ostaja_id'],
          'hintatarjous' => $rivi['hintatarjous'],
          'lisätietoja' => $rivi['lisätietoja'],
          'päivämäärä' => $rivi['päivämäärä']
          ));

        return $tarjous;
      }

      return null;
    }

  	public function tallenna(){
  		$query = DB::connection()->prepare('INSERT INTO Tarjous (tuote_id, ostaja_id, hintatarjous, lisätietoja) VALUES (:tuote_id, :ostaja_id, :hintatarjous, :lisatietoja) RETURNING id');

  		$hinta = str_replace(',','.', $this->hintatarjous);

      	$this->hintatarjous = $hinta;

  		$query->execute(array('tuote_id' => $this->tuote_id, 'ostaja_id' => $this->ostaja_id, 'hintatarjous' => $this->hintatarjous, 'lisatietoja' => $this->lisätietoja));

  		$rivi = $query->fetch();

  		$this->id = $rivi['id'];
  	}

    public function päivitä(){
      $query = DB::connection()->prepare('UPDATE Tarjous SET hintatarjous = :hintatarjous, lisätietoja = :lisatietoja, päivämäärä = CURRENT_DATE WHERE id = :id');
      $query->execute(array('hintatarjous' => $this->hintatarjous, 'lisatietoja' => $this->lisätietoja, 'id' => $this->id));
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
  		$query = DB::connection()->prepare('SELECT ta.id, ta.tuote_id, ta.ostaja_id, tu.kuvaus, ta.hintatarjous, ta.lisätietoja, ta.päivämäärä, k.nimi FROM Tarjous ta INNER JOIN Tuote tu ON ta.tuote_id = tu.id INNER JOIN Käyttäjä k ON k.id = ta.ostaja_id WHERE tu.myyjä_id = :id');
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

    public function poista(){
      $query = DB::connection()->prepare('DELETE FROM Tarjous WHERE id = :id');
      $query->execute(array('id' => $this->id));
    }

    public static function etsi_ostajan_id($tarjous_id){
      $query = DB::connection()->prepare('SELECT ostaja_id FROM Tarjous WHERE id = :id LIMIT 1');
      $query->execute(array('id' => $tarjous_id));
      $rivi = $query->fetch();
      $ostaja_id = $rivi['ostaja_id'];

      return $ostaja_id;
    }

    public function validate_hintatarjous(){
      $errors = array();

      if($this->hintatarjous == '' || $this->hintatarjous == null){
        $errors[] = 'Hintatarjous ei voi olla tyhjä!';
      }

      $hintatarjous_ilman_välipistettä = str_replace('.','', $this->hintatarjous);

      if(preg_match('/\D/', $hintatarjous_ilman_välipistettä)){
        $errors[] = 'Hintatarjous saa koostua ainoastaan numeroista tai eurot ja sentit erottavasta pisteestä!';
        return $errors;
      }

      //Lisätään hintaan sentit jos niitä ei ole, validoinnin helpottamiseksi
      if(strpos($this->hintatarjous, '.') == false){
        $this->hintatarjous = $this->hintatarjous . '.00';
      }

      $pituus_ilman_senttejä = strpos($this->hintatarjous, '.');

      if(strlen($this->hintatarjous) > $pituus_ilman_senttejä + 3){
        $errors[] = 'Eurot ja sentit erottavan pisteen jälkeen ei saa olla muuta kuin 2 numeroa!';
      }

      if($pituus_ilman_senttejä > 13){
        $errors[] = 'Hintatarjouksen euroja kuvaava numero on oltava korkeintaan 13 merkkiä!';
      }

      if(is_numeric($this->hintatarjous) == false){
        $errors[] = 'Hintatarjouksen täytyy olla numero tai desimaaliluku!';
      }

      return $errors;
    }

    public function validate_lisätiedot(){
      $errors = array();
      
      if(parent::merkkijono_liian_pitkä($this->lisätietoja, 300)){
        $errors[] = 'Lisätiedot eivät voi olla yli 300 merkkiä!';
      }

      return $errors; 
    }


  }