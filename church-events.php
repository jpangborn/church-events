<?php
  // Church Events
  // A Plugin for Kirby
  require __DIR__ . DS . 'lib' . DS . 'calendar.php';

  // Blueprint
  $kirby->set('blueprint',  'calendar',        __DIR__ . DS . 'blueprints' . DS . 'calendar.yml');
  $kirby->set('blueprint',  'calendar-year',   __DIR__ . DS . 'blueprints' . DS . 'calendar-year.yml');
  $kirby->set('blueprint',  'calendar-day',    __DIR__ . DS . 'blueprints' . DS . 'calendar-day.yml');

  // Fields
  $kirby->set('field',      'calendarboard',   __DIR__ . DS . 'fields' . DS . 'calendarboard');

  // Templates
  $kirby->set('template',   'calendar',        __DIR__ . DS . 'templates' . DS . 'calendar.php');

  // Controllers
  $kirby->set('controller', 'calendar',        __DIR__ . DS . 'controllers' . DS . 'calendar.php');

  // Snippets

  // Languages
  $code = site()->multilang() ? site()->language()->code() : c::get('church-events.language', 'en');

  if(is_file(__DIR__ . DS . 'languages' . DS . $code . '.php')) {
    require_once(__DIR__ . DS . 'languages' . DS . $code . '.php');
  } else {
    require_once(__DIR__ . DS . 'languages' . DS . 'en.php');
  }
