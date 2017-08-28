<?php

  class Käyttäjä extends BaseModel{

  	public $id, $nimi, $puh, $sähköposti, $liittymispvm, $salasana, $salasana2;

  	public function __construct($attributes){
  		parent::__construct($attributes);
      $this->validators = array('validate_nimi', 'validate_puhelinnumero', 'validate_sähköposti', 'validate_salasana');
  	}

    /**
     * Funktio tarkistaa ovatko sen saamat käyttäjätunnus ja salasana tietokannassa
     */
  	public static function tunnistaudu($käyttäjätunnus, $salasana){
  		$query = DB::connection()->prepare('SELECT * FROM Käyttäjä WHERE nimi = :nimi AND salasana = :salasana LIMIT 1');
  		$query->execute(array('nimi' => $käyttäjätunnus, 'salasana' => $salasana));
  		$rivi = $query->fetch();
  		if($rivi){
  			$käyttäjä = new Käyttäjä(array(
  				'id' => $rivi['id'],
  				'nimi' => $rivi['nimi'],
  				'puh' => $rivi['puh'],
  				'sähköposti' => $rivi['sähköposti'],
  				'liittymispvm' => $rivi['liittymispvm'],
  				'salasana' => $rivi['salasana']
  				));

  			return $käyttäjä;
  		} else {
  			return null;
  		}
  	}

    /**
     * Funktio etsii tietokannasta halutun käyttäjän ja palauttaa sen tiedot
     */
  	public static function etsi($id){
  		$query = DB::connection()->prepare('SELECT * FROM Käyttäjä WHERE id = :id LIMIT 1');
  		$query->execute(array('id' => $id));
  		$rivi = $query->fetch();

  		if ($rivi) {
  			$käyttäjä = new Käyttäjä(array(
  				'id' => $rivi['id'],
  				'nimi' => $rivi['nimi'],
  				'puh' => $rivi['puh'],
  				'sähköposti' => $rivi['sähköposti'],
  				'liittymispvm' => $rivi['liittymispvm'],
  				'salasana' => $rivi['salasana']
  				));

  			return $käyttäjä;
  		} else {
			return null;
  		}
  	}

    /**
     * Funktion tallentaa tietokantaan käyttäjä olion jolle funktiota on kutsuttu
     */
    public function tallenna(){
      $query = DB::connection()->prepare('INSERT INTO Käyttäjä (nimi, puh, sähköposti, salasana) VALUES (:nimi, :puh, :sahkoposti, :salasana) RETURNING id');
      $query->execute(array('nimi' => $this->nimi, 'puh' => $this->puh, 'sahkoposti' => $this->sähköposti, 'salasana' => $this->salasana));

      $rivi = $query->fetch();

      $this->id = $rivi['id'];
    }

    /**
     * Funktio tarkistaa käyttäjä olion nimen ja palauttaa siinä olevat virheen
     */
    public function validate_nimi(){
      $errors = array();

      if(self::nimi_jo_käytössä($this->nimi)){
        $errors[] = 'Nimi on jo käytössä!';
      }

      if(preg_match('/\s/', $this->nimi)){
        $errors[] = 'Nimi ei saa sisältää välilyöntejä!';
      }

      if(parent::merkkijono_liian_lyhyt($this->nimi, 3)){
        $errors[] = 'Nimi oltava vähintään 3 merkkiä!';
      }

      if(parent::merkkijono_liian_pitkä($this->nimi, 20)){
        $errors[] = 'Nimi ei saa olla yli 20 merkkiä!';
      }

      return $errors;
    }

    /**
     * Funktio tarkistaa onko annettu nimi jo tietokannassa
     */
    public static function nimi_jo_käytössä($nimi){
      $query = DB::connection()->prepare('SELECT * FROM Käyttäjä WHERE nimi = :nimi');
      $query->execute(array('nimi' => $nimi));

      $rivi = $query->fetch();

      if($rivi){
        return true;
      } else {
        return false;
      }
    }

    /**
     * Funktio tarkistaa käyttäjä olion salasanan ja palauttaa siinä olevat virheet
     */
    public function validate_salasana(){
      $errors = array();

      if(strlen($this->salasana) < 4 || $this->salasana == '' || $this->salasana == null){
        $errors[] = 'Salasana oltava vähintään 4 merkkiä!';
      }

      if(parent::merkkijono_liian_pitkä($this->salasana, 20)){
        $errors[] = 'Salasana ei saa olla yli 20 merkkiä!';
      }

      if(!($this->salasana == $this->salasana2)) {
        $errors[] = 'Salasanat eivät ole samat';
      }

      return $errors;
    }

    /**
     * Funktio tarkistaa käyttäjä olion puhelinnumeron ja palauttaa siinä olevat virheet
     */
    public function validate_puhelinnumero(){
      $errors = array();

      if(parent::merkkijono_liian_pitkä($this->puh, 15)){
        $errors[] = 'Puhelinnumero ei voi olla yli 15 merkkiä!';
      }

      return $errors;
    }

    /**
     * Funktio tarkistaa käyttäjä olion sähköpostin ja palauttaa siinä olevat virheet
     */
    public function validate_sähköposti(){
      $errors = array();

      if(parent::merkkijono_liian_pitkä($this->sähköposti, 40)){
        $errors[] = 'Sähköposti ei voi olla yli 40 merkkiä!';
      }

      return $errors;
    }
  }	