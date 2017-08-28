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

      $errors = $tarjous->errors();

      if(count($errors) == 0){
  		  $tarjous->tallenna();

  		  Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous tehty tuotteesta!'));
      } else {
        $tuote = TuoteController::haetuote($id);

        View::make('tarjous/uusi.html', array('errors' => $errors, 'tarjous' => $tarjous, 'tuote' => $tuote));
      }

  	}

  	public static function käyttäjän_tekemät_tarjoukset($id){
  		return Tarjous::käyttäjän_tekemät_tarjoukset($id);
  	}

  	public static function käyttäjälle_tehdyt_tarjoukset($id){
  		return Tarjous::käyttäjälle_tehdyt_tarjoukset($id);
  	}

    public static function poista($id){
      $tarjous = new Tarjous(array('id' => $id));
      $tarjous->poista();

      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous poistettu!'));
    }

    public static function muokkaa($id){
      $ostaja_id = Tarjous::etsi_ostajan_id($id);

      if ($_SESSION['käyttäjä'] != $ostaja_id) {
        Redirect::to('/', array('error' => 'Et voi muokata muiden tarjouksia tai tarjousta ei ole olemassa'));
      }

      $tarjous = Tarjous::etsi($id);
      $tuote = Tuote::etsi($tarjous->tuote_id);
      $myyjä = Käyttäjä::etsi($tuote->myyjä_id);

      View::make('tarjous/muokkaa.html', array('tarjous' => $tarjous, 'tuote' => $tuote, 'myyjä' => $myyjä));
    }

    public static function päivitä($id){
      $tiedot = $_POST;

      $tarjous = new Tarjous(array(
        'id' => $id,
        'hintatarjous' => $tiedot['hintatarjous'],
        'lisätietoja' => $tiedot['lisätietoja']
        ));

      $tarjous->päivitä();

      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjousta muokattu onnistuneesti!'));
    }
  }