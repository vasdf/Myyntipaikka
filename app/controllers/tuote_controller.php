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

    public static function uusi() {
      View::make('tuote/uusi.html');
    }

    public static function tallenna(){
      $tiedot = $_POST;

      $tuote = new Tuote(array(
        'myyjä_id' => '1',
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
      $tuote = Tuote::find($id);

      View::make('tuote/muokkaa.html', array('attributes' => $tuote));
    }

    // public static function päivitä($id){
    //   $tiedot = $_POST;

    //   $attributes = array(
    //       'id' => $id,
    //       'kuvaus' => $tiedot['kuvaus'],
    //       'hinta' => $tiedot['hintapyyntö'],
    //       'lisätietoja' => $tiedot['lisätietoja']
    //     );
    //}
  }