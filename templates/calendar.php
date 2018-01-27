<?php snippet('html-start') ?>

<body class="no-sidebar">
  <div id="page-wrapper">

    <!-- Header -->
    <?php snippet('header', array('verse' => false)) ?>

    <div class="wrapper style2">
      <div class="title"><?= $page->title()->html() ?></div>
      <div id="main" class="container">

        <!-- Content -->
          <div id="content">
            <article class="box post">
              <?php snippet('page-header') ?>

              <div class="calendar">
                <!-- Calendar Heading -->
                <div class="calendar-head">
                  <span class="calendar-prev-month"><a href="<?= $prevMonthURL ?>">&#139;</a></span>
                  <span class="calendar-next-month"><a href="<?= $nextMonthURL ?>">&#155;</a></span>
                  <h2 class="calendar-title"><?= $curMonth->name() ?> <?= $curMonth->year() ?></h2>
                </div>

                <?php foreach($curMonth->weeks()->first()->days() as $day): ?>
                  <div class="calendar-day-title"><h3><?= $day->prev()->name() ?></h3></div>
                <?php endforeach ?>

                <!-- Calendar Days -->
                <?php foreach($curMonth->weeks() as $week): ?>
                  <?php foreach($week->days() as $day): ?>
                    <div class="calendar-day <?= r($day->prev()->month() != $curMonth, 'inactive') ?>">
                      <span class="calendar-day-name"><?= $day->prev()->name() ?></span>
                      <p class="calendar-day-number"><?= $day->prev()->int() ?></p>
                      <div class="calendar-events">
                        <?php if($curDay = $page->find('year-' . $curMonth->year() . '/day-' . $day->prev())): ?>
                          <?php foreach($curDay->events()->toStructure() as $event): ?>
                            <h4 class="calendar-event-title"><?= r($event->category() == 'private', 'Private Event', $event->title()->html()) ?></h4>
                            <p class="calendar-event-details">
                              <?= $event->startTime() . '-' . $event->endTime() ?><br>
                              <?= l($event->location()->value(), 'Location TBD') ?>
                            </p>
                          <?php endforeach ?>
                        <?php endif ?>
                      </div>
                    </div>
                  <?php endforeach ?>
                <?php endforeach ?>

                <!-- Handle Final Sunday -->
                <?php if(strtolower($curMonth->lastDay()->name()) == 'sunday'): ?>
                  <div class="calendar-day">
                    <span class="calendar-day-name"><?= $curMonth->lastDay()->name() ?></span>
                    <p class="calendar-day-number"><?= $curMonth->lastDay()->int() ?></p>
                    <div class="calendar-events">
                      <?php if($curDay = $page->find('year-' . $curMonth->year() . '/day-' . $curMonth->lastDay())): ?>
                        <?php foreach($curDay->events()->toStructure() as $event): ?>
                          <h4 class="calendar-event-title"><?= r($event->category() == 'private', 'Private Event', $event->title()->html()) ?></h4>
                          <p class="calendar-event-details">
                            <?= $event->startTime() . '-' . $event->endTime() ?><br>
                            <?= l($event->location()->value(), 'Location TBD') ?>
                          </p>
                        <?php endforeach ?>
                      <?php endif ?>
                    </div>
                  </div>
                <?php endif ?>
              </div>

            </article>
          </div>
      </div>
    </div>

    <!-- Footer -->
    <?php snippet('footer') ?>
  </div>

<?php snippet('html-end') ?>
