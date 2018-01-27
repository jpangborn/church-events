<?php
  return function($site, $pages, $page) {
    $month = param('month') ? param('month') : date('n');
    $year = param('year') ? param('year') : date('Y');

    // Create Calendar Month
    $calendar = new Calendar();
    $curMonth = $calendar->month($year, $month);

    $nextMonthURL = url::build(array(
      'params' => array(
        'month' => $curMonth->next()->month()->int(),
        'year' => $curMonth->next()->year())));

    $prevMonthURL = url::build(array(
      'params' => array(
        'month' => $curMonth->prev()->month()->int(),
        'year' => $curMonth->prev()->year())));

    return compact('curMonth', 'nextMonthURL', 'prevMonthURL');
  };
