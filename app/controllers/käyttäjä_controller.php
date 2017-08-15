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
  }