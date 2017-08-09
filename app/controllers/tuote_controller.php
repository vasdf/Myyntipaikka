<?php

  class TuoteController extends BaseController{

  	public static function lista(){
  		$tuotteet = Tuote::all();

  		View::make('tuote/lista.html', array('tuotteet' => $tuotteet));	
  	}

  	public static function näytä($id){
  		$tuote = Tuote::find($id);

  		View::make('tuote/tiedot.html', array('tuote'  => $tuote));
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

      $tuote->save();

      Redirect::to('/tuote/' . $tuote->id, array('message' => 'Tuote lisätty valikoimaan'));
    }
  }