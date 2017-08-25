<?php
  
  function onko_kirjautunut(){
    BaseController::check_logged_in();
  }

  $routes->get('/', function() {
    View::make('home.html');
  });

  $routes->get('/tuotteet', function() {
    TuoteController::lista();
  });

  $routes->get('/tuote/uusi', 'onko_kirjautunut', function() {
    TuoteController::uusi();
  });

  $routes->post('/tuote', function() {
    TuoteController::tallenna();
  });

  $routes->get('/tuote/:id', 'onko_kirjautunut', function($id) {
    TuoteController::näytä($id);
  });

  $routes->get('/tuote/:id/muokkaa', 'onko_kirjautunut', function($id){
    TuoteController::muokkaa($id);
  });

  $routes->post('/tuote/:id/muokkaa', function($id){ 
    TuoteController::päivitä($id);
  });

  $routes->get('/kirjaudu', function() {
    KäyttäjäController::kirjaudu();
  });

  $routes->post('/kirjaudu', function() {
    KäyttäjäController::käsittele_kirjautuminen();
  });

  $routes->post('/kirjaudu_ulos', function() {
    KäyttäjäController::kirjaudu_ulos();
  });

  $routes->get('/profiili/:id', 'onko_kirjautunut', function($id) {
    KäyttäjäController::näytä($id);
  });

  $routes->post('/tuote/:id/poista', function($id){
    TuoteController::poista($id);
  });

  $routes->get('/rekisteroidy', function() {
    KäyttäjäController::rekisteröidy();
  });

  $routes->post('/kayttaja', function() {
    KäyttäjäController::tallenna();
  });

  $routes->get('/tuote/:id/tarjous', 'onko_kirjautunut', function($id) {
    TarjousController::uusi($id);
  });

  $routes->post('/tuote/:id/tarjous', function($id) {
    TarjousController::tallenna($id);
  });