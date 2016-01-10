<?php
namespace Application\Model;

use Ouzo\Model;
use Ouzo\Restrictions;
use Ouzo\Session;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Clock;
use Ouzo\Utilities\Json;

/**
 * @property string name
 * @property string params
 * @property string created_at
 * @property int id
 */
class Event extends Model
{
    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['id', 'name', 'params', 'created_at' => Clock::nowAsString()]
        ]);
    }

    /**
     * @return Event[]
     */
    public static function loadNew()
    {
        $lastEventId = Session::get('last_event_id');
        if (!$lastEventId) {
            //do not load events that we triggered before this session was started
            $lastEvent = Event::queryBuilder()->limit(1)->order('id desc')->fetch();
            Session::set('last_event_id', $lastEvent ? $lastEvent->id : 0);
        }

        $events = Event::where(['id' => Restrictions::greaterThan($lastEventId)])->order('id asc')->fetchAll();
        if ($events) {
            Session::set('last_event_id', Arrays::last($events)->id);
        }
        return $events;
    }

    /**
     * @return array
     */
    public function toJsonArray()
    {
        return [
            'name' => $this->name,
            'params' => Json::decode($this->params),
        ];
    }
}
