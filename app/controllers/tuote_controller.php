<?php

  class TuoteController extends BaseController{

  	public static function lista(){
  		$tuotteet = Tuote::kaikki();

  		View::make('tuote/lista.html', array('tuotteet' => $tuotteet));	
  	}

  	public static function näytä($id){
  		$tuote = Tuote::etsi($id);

      $myyjä = Käyttäjä::etsi($tuote->myyjä_id);

  		View::make('tuote/tiedot.html', array('tuote'  => $tuote, 'myyjä' => $myyjä));
  	}

    public static function käyttäjän_tuotteet($id){
      $tuotteet = Tuote::käyttäjän_tuotteet($id);

      return $tuotteet;
    }

    public static function uusi() {
      View::make('tuote/uusi.html');
    }

    public static function tallenna(){
      $tiedot = $_POST;


      $tuote = new Tuote(array(
        'myyjä_id' => $_SESSION['käyttäjä'],
        'kuvaus' => $tiedot['kuvaus'],
        'hinta' => $tiedot['hintapyyntö'],
        'lisätietoja' => $tiedot['lisätietoja'], 
        'lisäyspäivä' => '2017-01-02'
      ));

      $errors = $tuote->errors();

      if(count($errors) == 0){

        $tuote->save();

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

    public static function muokkaa($id){
      $myyjä_id = Tuote::etsi_tuotteen_myyjä($id);

      if (!BaseController::check_logged_in_id($myyjä_id)){

        Redirect::to('/', array('error' => 'Et voi muokata muiden tuotteita'));
      }


      $tuote = Tuote::etsi($id);

      View::make('tuote/muokkaa.html', array('attributes' => $tuote));
    }

    public static function päivitä($id){
      //$vanhatuote = Tuote::etsi($id);

      $tiedot = $_POST;

      $attributes = array(
          'id' => $id,
          //'myyjä_id' => $vanhatuote->myyjä_id,
          'kuvaus' => $tiedot['kuvaus'],
          'hinta' => $tiedot['hinta'],
          'lisätietoja' => $tiedot['lisätietoja']
        );

      $tuote = new Tuote($attributes);
      $errors = $tuote->errors();

      if(count($errors) > 0){
        View::make('tuote/muokkaa.html', array('errors' => $errors, 'attributes' => $attributes));
      } else {
        $tuote->päivitä();

        Redirect::to('/tuote/' . $tuote->id, array('message' => 'Tuotetta muokattu onnistuneesti!'));

      }
    }

    public static function poista($id){
      $tuote = new Tuote(array('id' => $id));
      $tuote->poista();

      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tuote poistettu!'));
    }
  }