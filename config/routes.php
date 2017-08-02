<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/item_list', function() {
  	HelloWorldController::item_list();
  });

  $routes->get('/login', function() {
    HelloWorldController::login();
  });

  $routes->get('/profile', function() {
    HelloWorldController::profile();
  });


  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
