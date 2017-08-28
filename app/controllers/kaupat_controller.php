<?php

  class KaupatController extends BaseController{

  	public static function tallenna($tarjous_id){
      $kaupat = new Kaupat(array(
      	'tarjous_id' => $tarjous_id
      	));

      $kaupat->tallenna();

      TarjousController::aseta_voimassa_false($tarjous_id);

      $tuote_id = TarjousController::hae_tuotteen_id($tarjous_id);
      TuoteController::aseta_myynnissä_false($tuote_id);


      Redirect::to('/profiili/' . $_SESSION['käyttäjä'], array('message' => 'Tarjous hyväksytty!'));
  	}

  	public static function käyttäjän_ostamat_tuotteet($käyttäjä_id){
  	  return Kaupat::käyttäjän_ostamat_tuotteet($käyttäjä_id);
  	}

  	public static function käyttäjän_myydyt_tuotteet($käyttäjä_id){
  	  return Kaupat::käyttäjän_myydyt_tuotteet($käyttäjä_id);

  	}
  }