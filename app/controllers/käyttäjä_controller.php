<?php
  
  class KäyttäjäController extends BaseController{

  	public static function kirjaudu(){
  		View::make('käyttäjä/kirjaudu.html');
  	}

  	public static function käsittele_kirjautuminen(){
  		$tiedot = $_POST;

  		$käyttäjä = Käyttäjä::tunnistaudu($tiedot['käyttäjätunnus'], $tiedot['salasana']);

  		if(!$käyttäjä){
  			View::make('käyttäjä/kirjaudu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'käyttäjätunnus' => $tiedot['käyttäjätunnus']));
  		} else {
  			$_SESSION['käyttäjä'] = $käyttäjä->id;

  			Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $käyttäjä->nimi . '!'));
  		}
  	}

    public static function kirjaudu_ulos(){
      $_SESSION['käyttäjä'] = null;
      Redirect::to('/kirjaudu', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function näytä($id){
      $käyttäjä = Käyttäjä::etsi($id);
      $käyttäjän_tuotteet = TuoteController::käyttäjän_tuotteet($id);

      View::make('käyttäjä/profiili.html', array('käyttäjä' => $käyttäjä, 'tuotteet' => $käyttäjän_tuotteet));
    }
  }