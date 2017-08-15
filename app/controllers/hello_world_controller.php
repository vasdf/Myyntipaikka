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

      $tuote = new Tuote(array(
          'id' => '5',
          'myyjä_id' => '2',
          'kuvaus' => 'assssssssssssssssssssssssssssssssssssssssssssadddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd',
          'hinta' => 'asdfCXZCDASDASDASDAfasdfasfasdas',
          'lisätietoja' => 'aasddddfsdafsdafasdfasdfasdfasdfasdfasdfasdfasdfasdffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff                                                                                                                                                asdasd',
          'lisäyspäivä' => '2000-01-01'
        ));

      Kint::dump($tuote);
      
      $errors = $tuote->errors();

      Kint::dump($errors);

    }
  }
