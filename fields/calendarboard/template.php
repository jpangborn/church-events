<?php
  $prevMonth = $currentMonth->prev();
  $nextMonth = $currentMonth->next();
?>

<div class="cf">
    <span class="month__prev" onclick="builtCalendarBoard('<?php echo $prevMonth->int() ?>','<?php echo $prevMonth->year()->int() ?>')">&#139;</span>
    <div class="calendar__title"><?php echo $currentMonth->name() . ' ' . $currentMonth->year() ?></div>
    <span class="month__next" onclick="builtCalendarBoard('<?php echo $nextMonth->int() ?>','<?php echo $nextMonth->year()->int() ?>')">&#155;</span>
</div>

<?php foreach($currentMonth->weeks()->first()->days() as $weekDay){ ?>
  <div class="weekday"><?php echo $weekDay->shortname() ?></div>
<?php } ?>


<!-- Calendar board days -->
<?php foreach($currentMonth->weeks() as $week){ ?>
  <?php foreach($week->days() as $day){ ?>

    <?php
      if ($day->month() != $currentMonth){
        $class=' is-sibling';
        $go = '';
      }else if ($day->isToday()){
        $class=' is-today';
        $go = 'href="' . $get_day_route_url . $day . '"';
      }else{
        $class = '';
        $go = 'href="' . $get_day_route_url . $day . '"';
      }
    ?>

    <a class="day<?php echo $class ?>" data-day="<?php echo $day ?>" <?php echo $go ?>>
      <p><?php echo $day->int() ?></p>
      <div class="events">

        <?php
          /* Events in days of current month */
          //if ($day->month() == $currentMonth){

            /* If day folder exists look for events */
            if(site()->find($calendarboard_url . $year_folder . '/day-' . $day)){

              $events = page($calendarboard_url . $year_folder . '/day-' . $day)->events()->toStructure();
              $i = 0;

              foreach($events as $event){

                  /* Check if spoiler is present */
                  if($spoiler__tpl != ''){

                    if ($i++ == 4){
                      echo '<i class="fa fa-ellipsis-h"></i>';
                      break;
                    }

                    $spoiler = $spoiler__tpl;
                    foreach($spoiler__fields[1] as $field){
                        $spoiler = str_replace('{{' . $field . '}}', $event->get($field), $spoiler);
                    }

                    echo '<div id="' . $event->guid() . '" class="event ' . $event->css() . '" title="' . $spoiler . '">';
                    echo $spoiler;
                    echo '</div>';

                  }else{
                    echo '<div class="event dot ' . $event->css() . '"></div>';
                  }
              }
            }
          //}
        ?>

      </div>
    </a>

  <?php } ?>
<?php } ?>
