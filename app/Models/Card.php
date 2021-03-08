<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 1:08 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Collections\RowFillPatterns;
use App\Models\Traits\IsValidRowFillPattern;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\InvalidArgumentException;
use App\Models\Collections\ValuePool;
use App\Models\Collections\Cells;

class Card extends Model
{
    use IsValidRowFillPattern;

    const ROWS = 3;
    const COLS = 9;
    const CELLS = 27;

    private Cells $cells;
    private RowFillPatterns $patterns;
    private ValuePool $valuePool;
    private int $gameId;

    // eager load cells
    protected $with = ['cells'];

    protected $fillable = [
        'game_id',
        'stripe_id',
    ];

    public function init(RowFillPatterns $patterns, ValuePool $valuePool, int $gameId) : void
    {
        // some basic validation here , can be more granular in real world
        if ($patterns->count() < self::ROWS) {
            throw new InvalidArgumentException("One or more arguments passed to Card are not valid");
        }
        $this->gameId = $gameId;
        $this->patterns = $patterns;
        $this->valuePool = $valuePool;
        $this->cells = new Cells();
        $this->fillCells();
    }

    private function fillCells() : void
    {
        for ($row = 0; $row < self::ROWS; $row++) {
            $rowCellsCreated = false;
            while (!$rowCellsCreated) {
                $pattern = $this->patterns->getPattern();
                $rowCellsCreated = $this->createCells($row, $pattern);
            }
            $this->valuePool->flushUsedItems();
        }
    }

    /**
     * This can be done better and more efficiently, if this was a real world application this method would be different
     */
    private function createCells(int $row, array $pattern) : bool
    {
        $cells = [];
        for ($col = 0; $col < self::COLS; $col++) {
            // for the backend are important only cells that have value, we can skip empty Cell. they are meaningful
            // only in ui
            if ($pattern[$col]) {
                $value = $this->valuePool->getValue($col);
                if ($value) {
                    $cell = new Cell();
                    $cell->init($value, $row, $col, false, $this->gameId);
                    $cells[] = $cell;
                } else {
                    return false;
                }

            }
        }

        // the cells should be added to the collection only if all non empty cols  are filled. This can be done better
        foreach ($cells as $cell) {
            $this->cells->add($cell);
        }

        return true;
    }

    public function save(array $options = [])
    {
        parent::save($options);
        $this->cells()->saveMany($this->cells);
    }

    public function cells() : HasMany
    {
        return $this->hasMany(Cell::class);
    }

    public function stripe() : BelongsTo
    {
        return $this->belongsTo(Stripe::class);
    }
}
