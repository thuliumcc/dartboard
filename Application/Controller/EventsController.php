<?php

namespace Application\Controller;


use Application\Model\Event;
use Ouzo\Controller;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Clock;
use Ouzo\Utilities\Json;

class EventsController extends Controller
{
    static $TIMEOUT = 12;

    function poll()
    {
        session_write_close();
        $stop = Clock::now()->plusSeconds(self::$TIMEOUT);
        while ($stop->isAfter(Clock::now())) {
            session_start();
            $events = Arrays::map(Event::loadNew(), function ($event) {
                return $event->toJsonArray();
            });
            session_write_close();

            if ($events) {
                $this->layout->renderAjax(Json::encode($events));
                return;
            }
            //usleep(100*1000);
        }
        $this->layout->renderAjax("[]");
    }
}