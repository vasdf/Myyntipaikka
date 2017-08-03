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

  $routes->get('/edit_item', function() {
    HelloWorldController::edit_item();
  });

  $routes->get('/add_item', function() {
    HelloWorldController::add_item();
  });

  $routes->get('/item_info', function() {
    HelloWorldController::item_info();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
