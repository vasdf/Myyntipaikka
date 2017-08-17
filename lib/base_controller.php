<?php

  class BaseController{

    public static function get_user_logged_in(){
      if(isset($_SESSION['käyttäjä'])){
        $käyttäjä_id = $_SESSION['käyttäjä'];

        $käyttäjä = Käyttäjä::etsi($käyttäjä_id);

        return $käyttäjä;
      }

      return null;
    }

    public static function check_logged_in(){
      if(!isset($_SESSION['käyttäjä'])){
        Redirect::to('/kirjaudu');
      }
    }

    public static function check_logged_in_id($id){
      $käyttäjä = self::get_user_logged_in();

      if($käyttäjä->id == $id){
        return true;
      } else {
        return false;
      }
    }
  }
