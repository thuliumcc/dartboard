<?php
namespace Application\Model;

use BadMethodCallException;
use Ouzo\Model;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Functions;

/**
 * @property int game_user_id
 * @property int field
 * @property int multiplier
 * @property int round
 * @property GameUser game_user
 */
class Hit extends Model
{
    /**
     * @var array
     */
    private static $multiplierCharMap = [
        's' => 1,
        'd' => 2,
        't' => 3
    ];

    public function __construct($attributes = [])
    {
        parent::__construct([
            'attributes' => $attributes,
            'fields' => ['game_user_id', 'field', 'multiplier', 'round'],
            'belongsTo' => [
                'game_user' => ['class' => 'GameUser', 'foreignKey' => 'game_user_id', 'referencedColumn' => 'id'],
            ]
        ]);
    }

    /**
     * @param string $field
     * @param GameUser $gameUser
     * @return Hit
     */
    public static function createFor($field, GameUser $gameUser)
    {
        if (preg_match('/(\d+)([sdt])/', $field, $matches)) {
            $multiplier = self::$multiplierCharMap[$matches[2]];
            return self::create(['game_user_id' => $gameUser->getId(), 'field' => intval($matches[1]), 'multiplier' => intval($multiplier), 'round' => $gameUser->game->round]);
        }
        throw new BadMethodCallException('Cannot parse field');
    }

    /**
     * @param Game $game
     * @return Hit[]
     */
    public static function findForGame(Game $game)
    {
        $gameUserIds = Arrays::map($game->game_users, Functions::extractId());
        return Hit::where(['game_user_id' => $gameUserIds])
            ->with('game_user->user')
            ->order('id desc')
            ->limit(9)
            ->fetchAll();
    }

    /**
     * @return bool
     */
    public function handleScore()
    {
        $isScored = $this->isScored();
        if ($isScored) {
            $this->updateScore();
        }
        return $isScored;
    }

    /**
     * @inheritdoc
     */
    public function isScored()
    {
        return $this->game_user->game->getEngine()->isScored($this->field, $this->multiplier);
    }

    /**
     * @inheritdoc
     */
    public function updateScore()
    {
        $this->game_user->game->getEngine()->updateScore($this->field, $this->multiplier);
    }
}
