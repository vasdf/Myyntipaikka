<?php
  
  /**
   * Luokka hallinnoi käyttäjien tekemiä tarjouksia muiden tuotteista
   */
  class TarjousController extends BaseController{

  	public static function uusi($id) {
  		$tuote = TuoteController::haetuote($id);

  		View::make('tarjous/uusi.html', array('tuote' => $tuote));
  	}

  	public static function tallenna($tuote_id){
  		$tiedot = $_POST;

      TarjousController::tarkista_onko_tarjouksen_tekeminen_sallittua($tuote_id, $tiedot);

  		$tarjous = new Tarjous(array(
  			'tuote_id' => $tuote_id,
  			'ostaja_id' => $_SESSION['käyttäjä'],
  			'hintatarjous' => $tiedot['hintatarjous'],
  			'lisätietoja' => $tiedot['lisätietoja1']
  		));

      $errors = $tarjous->errors();

      if(count($errors) == 0){
  		  $tarjous->tallenna();

  		  Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous tehty tuotteesta!'));
      } else {
        $tuote = TuoteController::haetuote($tuote_id);

        View::make('tarjous/uusi.html', array('errors' => $errors, 'tarjous' => $tarjous, 'tuote' => $tuote));
      }

  	}

  	public static function käyttäjän_tekemät_tarjoukset($käyttäjä_id){
  		return Tarjous::käyttäjän_tekemät_tarjoukset($käyttäjä_id);
  	}

  	public static function käyttäjälle_tehdyt_tarjoukset($käyttäjä_id){
  		return Tarjous::käyttäjälle_tehdyt_tarjoukset($käyttäjä_id);
  	}

    public static function poista($id){
      if(KaupatController::onko_tarjous_hyväksytty($id)){
        Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Tarjous, jonka yritit poistaa on jo hyväksytty!'));
      }

      $tarjous = new Tarjous(array('id' => $id));
      $tarjous->poista();

      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous poistettu!'));
    }

    public static function muokkaa($id){
      self::tarkista_onko_muokkaus_sallittua($id);

      $tarjous = Tarjous::etsi($id);
      $tuote = Tuote::etsi($tarjous->tuote_id);
      $myyjä = Käyttäjä::etsi($tuote->myyjä_id);

      View::make('tarjous/muokkaa.html', array('tarjous' => $tarjous, 'tuote' => $tuote, 'myyjä' => $myyjä));
    }

    public static function päivitä($id){ 

      self::tarkista_onko_muokkaus_sallittua($id);

      $tiedot = $_POST;

      $tarjous = new Tarjous(array(
        'id' => $id,
        'hintatarjous' => $tiedot['hintatarjous'],
        'lisätietoja' => $tiedot['lisätietoja'],
        'voimassa' => "TRUE"
        ));

      $errors = $tarjous->errors();

      if(count($errors) == 0){
        $tarjous->päivitä();

        Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjousta muokattu onnistuneesti!'));
      } else {
        View::make('tarjous/muokkaa.html', array('errors' => $errors, 'tarjous' => $tarjous));
      }
    }

    public static function aseta_voimassa_false($tarjous_id){
      $tarjous = Tarjous::etsi($tarjous_id);

      $tarjous->voimassa = "FALSE";

      $tarjous->päivitä();
    }

    public static function hae_tuotteen_id($tarjous_id){
      $tarjous = Tarjous::etsi($tarjous_id);

      return $tarjous->tuote_id;
    }

    public static function poista_tarjoukset_tuotteelle($id){
      Tarjous::poista_tarjoukset_tuotteelle($id);
    }

    public static function hae_tarjous($id){
      return Tarjous::etsi($id);
    }

    public static function onko_tarjous_voimassa($id){
      $tarjous = Tarjous::etsi($id);

      if($tarjous){
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /**
     * Funktio tarkistaa onko tarjous jo hyväksytty, poistettu tai jonkun muun käyttäjän,
     * jolloin tarjousta ei saa muokata
     */
    public static function tarkista_onko_muokkaus_sallittua($tarjous_id){
      if(KaupatController::onko_tarjous_hyväksytty($tarjous_id)){
        Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Tarjous, jota yritit muokata on jo hyväksytty!'));
      }

      $ostaja_id = Tarjous::etsi_ostajan_id($tarjous_id);
      if($ostaja_id == null){
        Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Tuote, johon tarjous liittyi, on poistettu tai tarjouksesi on hylätty!'));
      }

      if ($_SESSION['käyttäjä'] != $ostaja_id) {
        Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Et voi muokata muiden tarjouksia tai tarjousta ei ole olemassa'));
      }
    }

    /**
     * Funktion tarkistaa onko tuotetta, josta ollaan tekemässä tarjousta,
     * joko muokattu tai poistettu tietokannasta
     */
    public static function tarkista_onko_tarjouksen_tekeminen_sallittua($tuote_id, $käyttäjän_tiedot_tuotteesta){
      $tuote_tietokannassa = TuoteController::haetuote($tuote_id);

      if($tuote_tietokannassa == null){
        Redirect::to('/', array('error' => 'Tuote, josta olit tekemässä tarjousta, on poistettu!'));
      }

      if($käyttäjän_tiedot_tuotteesta['kuvaus'] != $tuote_tietokannassa->kuvaus || 
        $käyttäjän_tiedot_tuotteesta['hinta'] != $tuote_tietokannassa->hinta || 
        $käyttäjän_tiedot_tuotteesta['lisätietoja2'] != $tuote_tietokannassa->lisätietoja){
        Redirect::to('/', array('error' => 'Tuotetta, josta olit tekemässä tarjousta, on muokattu! Yritä uudestaan.'));
      }
    }
  }