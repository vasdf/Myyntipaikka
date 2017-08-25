<?php

  class TarjousController extends BaseController{

  	public static function uusi($id) {
  		$tuote = TuoteController::haetuote($id);

  		View::make('tarjous/uusi.html', array('tuote' => $tuote));
  	}

  	public static function tallenna($id){
  		$tiedot = $_POST;

  		$tarjous = new Tarjous(array(
  			'tuote_id' => $id,
  			'ostaja_id' => $_SESSION['käyttäjä'],
  			'hintatarjous' => $tiedot['hintatarjous'],
  			'lisätietoja' => $tiedot['lisätietoja']
  		));

  		$tarjous->tallenna();

  		Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous tehty tuotteesta!'));
  	}

  	public static function käyttäjän_tekemät_tarjoukset($id){
  		return Tarjous::käyttäjän_tekemät_tarjoukset($id);
  	}

  	public static function käyttäjälle_tehdyt_tarjoukset($id){
  		return Tarjous::käyttäjälle_tehdyt_tarjoukset($id);
  	}
  }