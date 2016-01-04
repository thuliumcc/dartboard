<?php

namespace Application\Controller;


use Application\Model\Event;
use Ouzo\Controller;
use Ouzo\Db\Stats;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Clock;
use Ouzo\Utilities\Json;

class EventsController extends Controller
{
    static $TIMEOUT = 12;

    function poll()
    {
        Stats::reset();
        session_write_close();
        $stop = Clock::now()->plusSeconds(self::$TIMEOUT);
        while ($stop->isAfter(Clock::now())) {
            session_start();
            $events = Event::loadNew();
            session_write_close();

            if ($events) {
                $this->layout->renderAjax(Json::encode(Arrays::map($events, function ($event) {
                    return $event->toJsonArray();
                })));
                return;
            }
            usleep(100*1000);
        }
        $this->layout->renderAjax("[]");
    }
}