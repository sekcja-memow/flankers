<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameInvite extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'code' => 'string'
    ];

    /**
     * Get game related to invite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Generate invite code randomly, and retry
     * up to five times if unique code exists
     *
     * @param \App\Models\Game $game
     * @param integer $retries
     * @return string
     */
    public static function generate(Game $game, $retries = 0)
    {
        $code = Str::upper(Str::random(6));

        try {
            $instance = new GameInvite();
            $instance->code = $code;
            $instance->game()->associate($game->id);
            $instance->save();

            return $instance;
        } catch (Exception $e) {
            if ($retries == 5)
                throw $e;
            return self::generate($game, $retries + 1);
        }
    }
}
