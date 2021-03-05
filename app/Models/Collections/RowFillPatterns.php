<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 12:46 PM
 */

namespace App\Models\Collections;


use App\Models\Contracts\RowFillPatternGenerator;
use App\Models\Traits\IsValidRowFillPattern;
use Illuminate\Support\Collection;

class RowFillPatterns extends Collection implements RowFillPatternGenerator
{
    use IsValidRowFillPattern;

    private $lastPattern;

    /**
     * get a random pattern that will be applied to a certain ticket row
     * @return array
     */
    public function getPattern() : array
    {
        $key = array_rand($this->items);
        $item = $this->get($key);
        $this->forget($key);
        return $item;
    }

    /**
     * 511 is 111111111, 1 and 0 represents the fills and gaps in the ticket row.
     * this way we can generate the permutations
     */
    public function generateRowFillPatterns() : void
    {
        for($n = 0; $n < 512; $n++) {
            $pattern = str_split(sprintf("%09d", decbin($n)), 1);
            if($this->isValid($pattern)) {
                $this->add($pattern);
            }
        }
        $this->shuffle();
    }
}
