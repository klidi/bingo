<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 4.3.21
 * Time: 1:03 PM
 */

namespace App\Models;


use App\Models\Collections\Cards;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    // holds array 1-90
    private array $ballNumbers;
    private bool $bingo = false;
    private Cards $winningCards;
    // real world app this would be collection of Ball valueObjects
    private array $balls;

    public function __construct(array $attributes = [])
    {
        $this->winningCards = new Cards();
        $this->ballNumbers = range(1, 90, 1);
        parent::__construct($attributes);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function stripes(): HasMany
    {
        return $this->hasMany(Stripe::class);
    }

    public function getBallRange(): array
    {
        return $this->ballRange();
    }

    public function unsetRangeElement(int $key)
    {
        unset($this->ballNumbers[$key]);
    }

    public function isBingo(): bool
    {
        return $this->bingo;
    }

    public function bingo() : void
    {
        $this->bingo = true;
    }

    public function addWinningCard(Card $card) : void
    {
        $this->winningCards->add($card);
    }

    public function nextBall()
    {
        if (count($this->ballNumbers) == 0) {
            throw new \Exception("No more balls :( !!!!!");
        }

        $key = array_rand($this->ballNumbers);
        // in real world this would be a value object
        $ball = $this->ballNumbers[$key];
        $this->unsetRangeElement($key);
        $this->balls[] = $ball;

        return $ball;
    }

    /**
     * this will return winning information.
     * real world this information would be a valueObject but i have spent so much time with this.
     * method return type would be something like EndGameInfo or GameResult or something similar
     * im returning an array just for the saving time.
     */
    public function terminate()
    {
        return [
            'game-balls' => $this->balls,
            'winning-cards' => $this->winningCards->toArray(),
        ];
    }
}
