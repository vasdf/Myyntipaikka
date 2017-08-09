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
  }