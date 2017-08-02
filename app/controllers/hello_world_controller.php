<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  echo 'Tämä on etusivu!';
    }

    public static function item_list(){
      View::make('suunnitelmat/item_list.html');
    }

    public static function login(){
      View::make('suunnitelmat/login.html');
    }

    public static function profile(){
      View::make('suunnitelmat/profile.html');
    }

    public static function sandbox(){
      View::make('helloworld.html');
    }
  }
