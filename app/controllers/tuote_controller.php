<?php
  /**
   * Luokka hallinnoin tuotteisiin liittyviä toimintoja
   */
  class TuoteController extends BaseController{

    /**
     * Funktio luo näkymän, joka sisältää kaikki tietokannan tuotteet
     */
  	public static function lista(){
  		$tuotteet = Tuote::kaikki();

  		View::make('tuote/lista.html', array('tuotteet' => $tuotteet));	
  	}

    /**
     * Funktio näyttää yhden tuotteen tietoja näkymässä
     */
  	public static function näytä($id){
  		$tuote = Tuote::etsi($id);

      if($tuote){
        $myyjä = Käyttäjä::etsi($tuote->myyjä_id);

  		  View::make('tuote/tiedot.html', array('tuote'  => $tuote, 'myyjä' => $myyjä));
      } else {
        Redirect::to('/', array('error' => 'Tuotetta ei ole olemassa!'));
      }

  	}

    /**
     * Funktio etsii tietyn käyttäjän tuotteet ja palauttaa ne
     */
    public static function käyttäjän_tuotteet($id){
      $tuotteet = Tuote::käyttäjän_tuotteet($id);

      return $tuotteet;
    }

    /**
     * Funktio hakee yhden tuotteen tietokannasta ja palauttaa sen
     */
    public static function haetuote($id){
      $tuote = Tuote::etsi($id);

      return $tuote;
    }

    public static function uusi() {
      View::make('tuote/uusi.html');
    }

    /**
     * Funktio luo uuden Tuote olion saamista tiedoistaa ja sen attribuuttien arvoista riippuen,
     * joko kutsuu Tuote luokan tallenna funktiota tai pyytää tietoja uudestaan ilmoittaen virheistä
     */
    public static function tallenna(){
      $tiedot = $_POST;


      $tuote = new Tuote(array(
        'myyjä_id' => $_SESSION['käyttäjä'],
        'kuvaus' => $tiedot['kuvaus'],
        'hinta' => $tiedot['hintapyyntö'],
        'lisätietoja' => $tiedot['lisätietoja'], 
        'lisäyspäivä' => '2000-01-01'
      ));

      $errors = $tuote->errors();

      if(count($errors) == 0){

        $tuote->tallenna();

        Redirect::to('/tuote/' . $tuote->id, array('message' => 'Tuote lisätty valikoimaan'));
      } else {
        $attributes = array(
          'kuvaus' => $tiedot['kuvaus'],
          'hinta' => $tiedot['hintapyyntö'],
          'lisätietoja' => $tiedot['lisätietoja']
        );

        View::make('tuote/uusi.html', array('errors' => $errors, 'attributes' => $attributes));  
      }
    }
    /**
     * Funktio luo muokkaus näkymän tietylle tuotteelle tarkistaen onko käyttäjällä oikeuksia sen muokkaukselle
     */
    public static function muokkaa($id){
      $myyjä_id = Tuote::etsi_tuotteen_myyjä($id);

      if ($_SESSION['käyttäjä'] != $myyjä_id){

        Redirect::to('/', array('error' => 'Et voi muokata muiden tuotteita tai tuotetta ei ole olemassa!'));
      }


      $tuote = Tuote::etsi($id);

      View::make('tuote/muokkaa.html', array('attributes' => $tuote));
    }

    /**
     * Funktio luo uuden Tuote olion saamiensa tietojen pohjalta ja sen attribuuttien perusteella
     * joko kutsuu Tuote luokan päivitä funktiota tai pyytyää tietoja uudestaa ilmoittaen niiden virheistä
     */
    public static function päivitä($id){
      if(KaupatController::onko_tuotteesta_tehty_kaupat($id)){
      	Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Et voi muokata tuotetta, koska siitä on jo tehty kaupat!'));
      }

      $tiedot = $_POST;

      $attributes = array(
          'id' => $id,
          'kuvaus' => $tiedot['kuvaus'],
          'hinta' => $tiedot['hinta'],
          'lisätietoja' => $tiedot['lisätietoja'],
          'myynnissä' => 'TRUE'
        );

      $tuote = new Tuote($attributes);
      $errors = $tuote->errors();

      if(count($errors) > 0){
        View::make('tuote/muokkaa.html', array('errors' => $errors, 'attributes' => $attributes));
      } else {
        //poistaa päivitetystä tuotteesta tehdyt tarjoukset, koska tuotteen tiedot muuttuneet
        TarjousController::poista_tarjoukset_tuotteelle($id);

        $tuote->päivitä();

        Redirect::to('/tuote/' . $tuote->id, array('message' => 'Tuotetta muokattu onnistuneesti!'));

      }
    }

    /**
     * Funktio kutsuu Tuote luokan funktiota poista halutulle tuotteelle
     */
    public static function poista($id){
      if(KaupatController::onko_tuotteesta_tehty_kaupat($id)){
      	Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('error' => 'Et voi poistaa tuotetta, koska siitä on jo tehty kaupat!'));
      }

      TarjousController::poista_tarjoukset_tuotteelle($id);

      $tuote = new Tuote(array('id' => $id));
      $tuote->poista();

      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tuote poistettu!'));
    }

    public static function aseta_myynnissä_false($tuote_id){
      $tuote = Tuote::etsi($tuote_id);

      $tuote->myynnissä = "FALSE";

      $tuote->päivitä();
    }
  }