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
    public static $TIMEOUT = 12;

    public function poll()
    {
        Stats::reset();
        session_write_close();
        $stop = Clock::now()->plusSeconds(self::$TIMEOUT);
        while (true) {
            if (!$stop->isAfter(Clock::now()) || connection_aborted()) {
                $this->layout->renderAjax("[]");
                return;
            }
            session_start();
            $events = Event::loadNew();
            session_write_close();

            if ($events) {
                $this->layout->renderAjax(Json::encode(Arrays::map($events, function ($event) {
                    return $event->toJsonArray();
                })));
                return;
            }
            Stats::reset();
            usleep(100*1000);
        }
    }
}
