<?php

  class Käyttäjä extends BaseModel{

  	public $id, $nimi, $puh, $sähköposti, $liittymispvm, $salasana;

  	public function __construct($attributes){
  		parent::__construct($attributes);
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
  }	