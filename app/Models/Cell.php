<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 10:07 PM
 */

namespace App\Models;

use Psr\Log\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cell extends Model
{
    protected $fillable = [
        'card_id',
        'game_id',
        'value',
        'row',
        'col',
        'checked'
    ];

    /**
     * // im doing this here cuz in the constructor it creates complications when declaring relationships
     *
     * @param int $value
     * @param int $row
     * @param int $col
     * @param bool $checked
     */
    public function init(int $value, int $row, int $col, bool $checked, int $gameId) : void
    {
        $this->value = $value;
        $this->row = $row;
        $this->col = $col;
        $this->checked = $checked;
        $this->game_id = $gameId;

        if (!$this->isValid()) {
            throw new InvalidArgumentException("One or more arguments passed to Cell object are invalid");
        }
    }

    /**
     * here we would check if the value received matches the bingo card rules based on row and col
     * due to time im just describing this and returning true
     * @return bool
     */
    public function isValid() :bool
    {
        return true;
    }

    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function scopeCheck($query, $gameId, $value)
    {
        return $query->where([
            ['game_id', '=', $gameId],
            ['value', '=', $value],
            ['checked', '=', false]
        ])
        ->update(['checked' => true]);
    }

    public function scopeChecked($query, $gameId)
    {
        return $query->where([
            ['game_id', '=', $gameId],
            ['checked', '=', true]
        ]);
    }

    public function scopeFilledLines($query, $id)
    {
        return $query->select('card_id', 'row')
            ->checked($id)
            ->havingRaw('COUNT(card_id) >= 5')
            ->havingRaw('COUNT(row) = 5')
            ->groupBy('card_id', 'row')
            ->get();
    }
}
