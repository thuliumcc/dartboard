<?php

namespace Application\Controller;


use Application\Model\Event;
use Ouzo\Controller;
use Ouzo\Utilities\Clock;
use Ouzo\Utilities\Json;

class EventsController extends Controller
{
    static $TIMEOUT = 12;

    function poll()
    {
        session_write_close();
        $start = Clock::now();
        while ($start->plusSeconds(self::$TIMEOUT)->isAfter(Clock::now())) {

            ini_set('session.use_only_cookies', false);
            ini_set('session.use_cookies', false);
            ini_set('session.use_trans_sid', false);
            ini_set('session.cache_limiter', null);
            session_start();

            $events = Event::loadNew();
            if ($events) {
                $this->layout->renderAjax(Json::encode($events));
                return;
            }
            session_write_close();
            usleep(1000);
        }
        $this->layout->renderAjax("[]");
    }
}