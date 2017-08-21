<?php

  class Käyttäjä extends BaseModel{

  	public $id, $nimi, $puh, $sähköposti, $liittymispvm, $salasana, $salasana2;

  	public function __construct($attributes){
  		parent::__construct($attributes);
      $this->validators = array('validate_nimi', 'validate_puhelinnumero', 'validate_sähköposti', 'validate_salasana');
  	}

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

    public function tallenna(){
      $query = DB::connection()->prepare('INSERT INTO Käyttäjä (nimi, puh, sähköposti, salasana) VALUES (:nimi, :puh, :sahkoposti, :salasana) RETURNING id');
      $query->execute(array('nimi' => $this->nimi, 'puh' => $this->puh, 'sahkoposti' => $this->sähköposti, 'salasana' => $this->salasana));

      $rivi = $query->fetch();

      $this->id = $rivi['id'];
    }

    public function validate_nimi(){
      $errors = array();

      $nimi = str_replace(' ', '', $this->nimi);

      if(strlen($nimi) < 3 || $nimi == '' || $nimi == null){
        $errors[] = 'Nimi oltava vähintään 3 merkkiä!';
      }

      if(strlen($this->nimi) > 20){
        $errors[] = 'Nimi ei saa olla yli 20 merkkiä!';
      }

      return $errors;
    }

    public function validate_salasana(){
      $errors = array();

      if(strlen($this->salasana) < 4 || $this->salasana == '' || $this->salasana == null){
        $errors[] = 'Salasana oltava vähintään 4 merkkiä!';
      }

      if(strlen($this->salasana) > 20){
        $errors[] = 'Salasana ei saa olla yli 20 merkkiä!';
      }

      if(!($this->salasana == $this->salasana2)) {
        $errors[] = 'Salasanat eivät ole samat';
      }

      return $errors;
    }

    public function validate_puhelinnumero(){
      $errors = array();

      if(strlen($this->puh) > 15){
        $errors[] = 'Puhelinnumero ei voi olla yli 15 merkkiä!';
      }

      return $errors;
    }

    public function validate_sähköposti(){
      $errors = array();

      if(strlen($this->sähköposti) > 40){
        $errors[] = 'Sähköposti ei voi olla yli 40 merkkiä!';
      }

      return $errors;
    }
  }	