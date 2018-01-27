<?php
/**
 * Calendar Board Field for Kirby CMS (v. 2.2.3)
 *
 * @author    Marco Oliva - team@moloc.net
 * @version   0.6
 *
 */

include_once __DIR__ . DS . 'lib' . DS . 'calendar.php';

class CalendarboardField extends BaseField {

  static public $assets = array(
      'js' => array(
          'calendarboard.js'
      ),
      'css' => array(
          'calendarboard.css'
      )
  );

  public function check_day($calendarboard_url, $date){

    $Date   = str::split($date, '-');
    $year   = $Date[0];
    $month  = $Date[1];
    $day    = $Date[2];

    $year_folder  = 'year-' . $year;
    $day_folder   = 'day-' . $date;

    // Check Year folder existence
    if(!site()->find($calendarboard_url . '/' . $year_folder)){
      page($calendarboard_url)->children()->create($year_folder, 'calendar-year', array(
        'title' => 'year-' . $year
      ));
    }

    // Check Day folder existence
    if(!site()->find($calendarboard_url . '/' . $year_folder . '/' . $day_folder)){
      page($calendarboard_url . '/' . $year_folder)->children()->create($day_folder, 'calendar-day', array(
        'title' => $day . '-' . $month . '-' . $year
      ));
    }
  }

  public function routes() {
    return array(
      array(
        'pattern' => 'get-month-board/(:num)/(:num)',
        'method'  => 'get',
        'action'  => 'getMonthBoard'
      ),
      array(
        'pattern' => 'get-day/(:any)',
        'method'  => 'get',
        'action'  => 'getDay'
      ),
      array(
        'pattern' => 'move-event/(:any)/(:any)/(:any)',
        'method'  => 'get',
        'action'  => 'moveEvent'
      )
    );
  }

  public function __construct() {
    $this->type   = 'calendarboard';
    $this->label  = l::get('fields.calendarboard.label', 'Calendar Board');
  }

  public function content() {
    $calendarBoard = brick('div');
    $calendarBoard->attr('id','calendarboard');
    $calendarBoard->data("name", $this->name);
    $calendarBoard->data("month", s::get('cb__month', date("m")));
    $calendarBoard->data("year", s::get('cb__year', date("Y")));
    $calendarBoard->data("field", "createCalendar");

    return $calendarBoard;
  }
}

$calendarboard = new CalendarboardField();

// Cloning event
// Know Kirby issue: https://github.com/getkirby/panel/issues/688
kirby()->hook('panel.page.update', function($page) use ($calendarboard){

    $slug = $page->slug();

    if(str::startsWith($slug, 'day-20')){

      $date = str_replace('day-', '', $slug);

      $cal = new Calendarboard\calendar();
      $calendarboard_url = $page->parent()->parent()->uri();
      $page_date = $cal->date($date);

      // Events of the current date
      $events = $page->events()->yaml();

      foreach($events as $key => $value){

        // create GUID if not exist
        /*
        if(empty($events[$key]['guid'])){
          $events[$key]['guid'] = GUID();
        }
        */

        if(!empty($events[$key]['cloning'])){
          $cloning_time = $events[$key]['cloning'];
        }else{
          $cloning_time = '';
        }

        // Delete cloning data
        unset($events[$key]['cloning']);

        // Check if cloning field is not empty
        if($cloning_time != ''){

          $event_to_clone = $events[$key];
          $day_date = $page_date;

          for($i=0; $i<$cloning_time; $i++){

            // Next date
            $day_date     = $day_date->next();
            $day_date_url = $calendarboard_url . '/year-' . $day_date->year()->int() . '/day-' . $day_date;

            // Check next date existence
            $calendarboard->check_day($calendarboard_url, $day_date);

            // Events of the next date
            $events_next_day = page($day_date_url)->events()->yaml();

            // Add cloning event
            $events_next_day[] = $event_to_clone;

            // Update next date page
            page($day_date_url)->update(array(
               'events' => yaml::encode($events_next_day)
            ));

          }
        }
      }

      // Update current date page
      $page->update(array(
         'events' => yaml::encode($events)
      ));

    }
});

/*
function GUID(){
  return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
*/
