<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 10:43 PM
 */

namespace App\Models;

use App\Models\Collections\Cards;
use App\Models\Collections\RowFillPatterns;
use App\Models\Collections\ValuePool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * I want this to be an atomic operation.
 * Stripe class will orchestrate the hole process.
 *
 * Class Stripe
 * @package App\Models
 */
class Stripe extends Model
{
    /**
     * the limit is 4, in order to make 6 tickets per stripe
     * i need to improve the row pattern picking algorithm not just relying on random
     * basically i will need to pick patterns that can balance the valuePool.
     * with a limit of 5 as it is right now in 90% of cases it will generate a stripe
     */
    private const LENGTH = 4;
    private const FILL_PATTERNS_CACHE_KEY = 'ticket-permutations';

    private RowFillPatterns $patterns;
    private ValuePool $valuePool;
    private Cards $cardsCollection;

    // eager load cards
    protected $with = ['cards'];

    public function init(int $gameId, int $userId)
    {
        $this->getRowFillPatterns();

        $this->game_id = $gameId;
        $this->user_id = $userId;

        $this->valuePool = new ValuePool();
        $this->valuePool->generatePool();

        $this->cardsCollection = new Cards();

        $this->generateCards($gameId);
    }

    private function generateCards(int $gameId)
    {
        for ($i = 1; $i <= self::LENGTH; $i++) {
            $card = new Card();
            $card->init($this->patterns, $this->valuePool, $gameId);
            $this->cardsCollection->add($card);
        }
    }

    public function save(array $options = []) : bool
    {
        DB::transaction(function () {
            parent::save([]);
            $this->cards()->saveMany($this->cardsCollection);
        });
        return $this->id ? true : false;
    }

    private function getRowFillPatterns() : void
    {
        if (Cache::has(self::FILL_PATTERNS_CACHE_KEY)) {
            $this->patterns = new RowFillPatterns();
            $this->patterns->generateRowFillPatterns();
            Cache::put(self::FILL_PATTERNS_CACHE_KEY, $this->patterns, Carbon::now()->addYear());
        } else {
            $this->patterns = new RowFillPatterns(Cache::get(self::FILL_PATTERNS_CACHE_KEY));
        }
    }

    public function cards() : HasMany
    {
        return $this->hasMany(Card::class);
    }
}
