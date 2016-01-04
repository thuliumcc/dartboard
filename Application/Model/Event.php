<?php

namespace Application\Model;


use Ouzo\Model;
use Ouzo\Restrictions;
use Ouzo\Session;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Clock;

/**
 * @property string name
 * @property string params
 * @property string created_at
 * @property int id
 */
class Event extends Model
{
    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'attributes' => $attributes,
            'fields' => ['id', 'name', 'params', 'created_at' => Clock::nowAsString()]
        ));
    }

    public static function loadNew()
    {
        $lastEventId = Session::get('last_event_id') ?: 0;
        $events = Event::where(['id' => Restrictions::greaterThan($lastEventId)])->order('id asc')->fetchAll();
        if ($events) {
            Session::set('last_event_id', Arrays::last($events)->id);
        }
        return $events;
    }
}