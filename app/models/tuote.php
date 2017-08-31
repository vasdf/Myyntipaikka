<?php

  class Tuote extends BaseModel{

  	public $id, $myyjä_id, $myyjä_nimi, $kuvaus, $hinta, $lisätietoja, $lisäyspäivä, $myynnissä;

  	public function __construct($attributes){
  		parent::__construct($attributes);
      $this->validators = array('validate_kuvaus', 'validate_hinta', 'validate_lisätiedot');
  	}

    /**
     * Funktio etsii tietokannasta kaikki tuotteen ja palauttaa ne
     */
  	public static function kaikki(){
  		$query = DB::connection()->prepare('SELECT t.id, t.myyjä_id, k.nimi, t.kuvaus, t.hinta, t.lisätietoja, t.lisäyspäivä FROM Tuote t INNER JOIN Käyttäjä k ON t.myyjä_id = k.id WHERE t.myytävänä = TRUE');
  		$query->execute();
  		$rivit = $query->fetchAll();
  		$tuotteet = array();

  		foreach($rivit as $rivi){

  			$tuotteet[] = new Tuote(array(
  				'id' => $rivi['id'],
  				'myyjä_id' => $rivi['myyjä_id'],
          'myyjä_nimi' => $rivi['nimi'],
  				'kuvaus' => $rivi['kuvaus'],
  				'hinta' => $rivi['hinta'],
  				'lisätietoja' => $rivi['lisätietoja'],
  				'lisäyspäivä' => $rivi['lisäyspäivä']
  				));
  		}

  		return $tuotteet;
  	}

    /**
     * Funktio etsii tietokannasta halutun tuotteen id:n perusteella ja palauttaa sen
     */
  	public static function etsi($id){
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
  				'lisäyspäivä' => $rivi['lisäyspäivä'],
          'myynnissä' => $rivi['myytävänä']
  				));

  			return $tuote;
  		}

      return null;
  	}

    /**
     * Funktio etsii tietokannasta kaikki tietyn käyttäjän tuotteet käyttäjän id:n perusteella
     * ja palauttaa tuotteet
     */
    public static function käyttäjän_tuotteet($id){
      $query = DB::connection()->prepare('SELECT * FROM Tuote WHERE myyjä_id = :myyja_id AND myytävänä = TRUE');
      $query->execute(array('myyja_id' => $id));
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

    /**
     * Funktio etsii halutun tuotteen myyjä_id:n tuotteen id:n perusteella ja palauttaa myyjä_id:n
     */
    public static function etsi_tuotteen_myyjä($id){
      $query = DB::connection()->prepare('SELECT myyjä_id FROM Tuote WHERE id = :id LIMIT 1');
      $query->execute(array('id' => $id));
      $rivi = $query->fetch();

      $myyjä_id = $rivi['myyjä_id'];

      return $myyjä_id;

    }
    /**
     * Funktio tallentaa Tuote olion, jolle funktiota kutsuttiin, tietokantaan
     */
    public function tallenna(){
      $query = DB::connection()->prepare('INSERT INTO Tuote (myyjä_id, kuvaus, hinta, lisätietoja, lisäyspäivä) VALUES (:myyjaid, :kuvaus, :hinta, :lisatietoja, CURRENT_DATE) RETURNING id');

      $query->execute(array('myyjaid' => $this->myyjä_id, 'kuvaus' => $this->kuvaus, 'hinta' => floatval($this->hinta), 'lisatietoja' => $this->lisätietoja));

      $rivi = $query->fetch();

      $this->id = $rivi['id'];
    }

    /**
     * Funktio muokkaa Tuote olion, jolle funktiota kutsuttiin, tietoja tietokannasssa
     */
    public function päivitä(){
      $query = DB::connection()->prepare('UPDATE Tuote SET kuvaus = :kuvaus, hinta = :hinta, lisätietoja = :lisatietoja, lisäyspäivä = CURRENT_DATE, myytävänä = :myynnissa WHERE id = :id');

      $query->execute(array('kuvaus' => $this->kuvaus, 'hinta' => $this->hinta, 'lisatietoja' => $this->lisätietoja, 'id' => $this->id, 'myynnissa' => $this->myynnissä));

    }

    /**
     * Funktio poistaa Tuote olion jolle funktiota kutsutiinm tietokannasta
     */
    public function poista(){
      $query = DB::connection()->prepare('DELETE FROM Tuote WHERE id = :id');
      $query->execute(array('id' => $this->id));
    }

    /**
     * Funktio tarkistaa Tuote olion kuvauksen ja palauttaa siinä olevat virheet
     */
    public function validate_kuvaus(){
      $errors = array();

      if(parent::merkkijono_liian_lyhyt($this->kuvaus, 3)){
        $errors[] = 'Kuvaus pitää olla vähintään 3 merkkiä!';
      }

      if(parent::merkkijono_liian_pitkä($this->kuvaus, 30)){
        $errors[] = 'Kuvaus ei saa olla yli 30 merkkiä!';
      } 

      return $errors;
    }

    /**
     * Funktio tarkistaa Tuote olion hinnan ja palauttaa siinä olevat virheet
     */
    public function validate_hinta(){
      $errors = array();

      if($this->hinta == '' || $this->hinta == null){
        $errors[] = 'Hinta ei voi olla tyhjä!';
      }

      //Lisätään hintaan sentit jos niitä ei ole validoinnin helpottamiseksi
      if(strpos($this->hinta, '.') == false){
        $this->hinta = $this->hinta . '.00';
      }

      $hinta_ilman_välipistettä = str_replace('.','', $this->hinta);

      //Tarkistetaan ettei hinta sisällä muuta kuin numeroita
      if(preg_match('/\D/', $hinta_ilman_välipistettä)){
        $errors[] = 'Hinta saa koostua ainoastaan numeroista tai eurot ja sentit erottavasta pisteestä!';
        return $errors;
      }

      $pituus_ilman_senttejä = strpos($this->hinta, '.');

      //Tarkistetaan ettei senttejä merkitseviä numeroita ole liikaa
      if(strlen($this->hinta) > $pituus_ilman_senttejä + 3){
        $errors[] = 'Eurot ja sentit erottavan pisteen jälkeen ei saa olla muuta kuin 2 numeroa!';
      }

      if($pituus_ilman_senttejä > 13){
        $errors[] = 'Hinnan euroja kuvaava numero on oltava korkeintaan 13 merkkiä!';
      }

      if(is_numeric($this->hinta) == false){
        $errors[] = 'Hinta täytyy olla numero tai desimaaliluku!';
      }

      return $errors;
    }

    /**
     * Funktio tarkistaa Tuote olion lisätiedot ja palauttaa siinä olevat virheet
     */
    public function validate_lisätiedot(){
      $errors = array();

      if(parent::merkkijono_liian_pitkä($this->lisätietoja, 300)){
        $errors[] = 'Lisätiedot eivät voi olla yli 300 merkkiä!';
      }

      return $errors;
    }
  }