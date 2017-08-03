<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      View::make('home.html');
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

    public static function edit_item(){
      View::make('suunnitelmat/edit_item.html');
    }

    public static function add_item(){
      View::make('suunnitelmat/add_item.html');
    }

    public static function item_info(){
      View::make('suunnitelmat/item_info.html');
    }

    public static function sandbox(){
      View::make('helloworld.html');
    }
  }
