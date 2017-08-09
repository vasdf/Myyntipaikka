<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      View::make('home.html');
    }

    public static function item_list(){
      View::make('tuote/lista.html');
    }

    public static function login(){
      View::make('suunnitelmat/login.html');
    }

    public static function profile(){
      View::make('suunnitelmat/profile.html');
    }

    public static function edit_item(){
      View::make('tuote/edit_item.html');
    }

    public static function add_item(){
      View::make('tuote/uusi.html');
    }

    public static function item_info(){
      View::make('tuote/item_info.html');
    }

    public static function sandbox(){
      $tuoli = Tuote::find(1);
      $tuotteet = Tuote::all();

      Kint::dump($tuotteet);
      Kint::dump($tuoli);
    }
  }
