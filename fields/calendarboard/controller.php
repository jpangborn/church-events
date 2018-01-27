<?php

class CalendarboardFieldController extends Kirby\Panel\Controllers\Field {

  public function getMonthBoard($month, $year) {
    
    // Calendar language
    date_default_timezone_set('UTC');
    $l = panel()->language();
    setlocale(LC_ALL, $l . '_' . str::upper($l)); 
    //setlocale(LC_ALL, 'us_US'); 
    
    // Get spoiler string and extract field keys
    $spoiler__tpl = $this->field()->spoiler();
    preg_match_all('/\{{(.*?)\}}/', $spoiler__tpl, $spoiler__fields); 
    
    // Calendar stuff
    $cal = new Calendarboard\calendar();
    $currentMonth = $cal->month($year, $month);

    return tpl::load(__DIR__ . DS . 'template.php', array(
        'currentMonth' => $currentMonth,
        'get_day_route_url' => purl($this->model(), 'field/' . $this->field()->name . '/calendarboard/get-day/'),
        'calendarboard_url' => $this->model(),
        'year_folder' => '/year-' . $year,
        'spoiler__tpl' => $spoiler__tpl,
        'spoiler__fields' => $spoiler__fields
    ));   
  }
  
  public function getDay($date) {
    
    $Date = str::split($date, '-');
    
    s::start();
    s::set('cb__month', $Date[1]);
    s::set('cb__year', $Date[0]);
    
    // If day folder doesn't exists, create it         
    $this->field()->check_day($this->model(), $date);
    
    // Go to day edit page
    go(purl($this->model(), 'year-' . $Date[0] . '/day-' . $date . '/edit'));  
  } 

  public function moveEvent($index, $fromDay, $toDay) {
  
    $fromDate = str::split($fromDay, '-');
    s::start();
    s::set('cb__month', $fromDate[1]);
    s::set('cb__year', $fromDate[0]);  
  
    if ($fromDay != $toDay){
       $pageFrom = page($this->model() . '/year-' . $fromDate[0] . '/day-' . $fromDay);
       $eventsFrom = $pageFrom->events()->yaml();
       
       $this->field()->check_day($this->model(), $toDay);
       $toDate = str::split($toDay, '-');
       $pageTo = page($this->model() . '/year-' . $toDate[0] . '/day-' . $toDay); 
       $eventsTo = $pageTo->events()->yaml();   
       
       $eventToMove = $eventsFrom[$index];
       unset($eventsFrom[$index]);
       $eventsFrom = array_values($eventsFrom);
       
        $pageFrom->update(array(
           'events' => yaml::encode($eventsFrom)
        ));
        
        $eventsTo[] = $eventToMove;
        
        $pageTo->update(array(
           'events' => yaml::encode($eventsTo)
        ));
      }

    go(purl($this->model(), 'edit'));      
   
  }  

}