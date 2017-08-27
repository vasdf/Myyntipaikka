<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
        $errors = array_merge($errors, $this->{$validator}());
      }

      return $errors;
    }

    public function merkkijono_liian_pitkä($merkkijono, $maxpituus){
      if(strlen($merkkijono) > $maxpituus){
        return true;
      }else{
        return false;
      }
    }

    public function merkkijono_liian_lyhyt($merkkijono, $minpituus){
       $merkkijono2 = str_replace(" ", "", $merkkijono);

       if($merkkijono2 == '' || $merkkijono2 == null || strlen($merkkijono2) < $minpituus){
        return true;
       }else{
        return false;
       }
    }
  }
