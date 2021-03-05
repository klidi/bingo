<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 7:38 PM
 */

namespace App\Models\Collections;


use App\Models\Contracts\ValuePoolGenerator;
use Illuminate\Support\Collection;

class ValuePool extends Collection implements ValuePoolGenerator
{
    private $keys = [];

    /**
     * this generates range of values 1-90 and groups them same as 90-ball bingo card columns
     * @return array
     */
    public function generatePool() : void
    {
        $chunckedRange[] = range(1, 9, 1);
        $range = range(10, 79, 1);
        $this->items = array_merge($chunckedRange, array_chunk($range, 10));
        $this->items[] = range(80, 90, 1);
    }

    /**
     *
     * get a random value based on column
     *
     * @param int $key
     * @return int|null
     */
    public function getValue(int $key) : ?int
    {
        $value = null;

        $item = $this->get($key);
        if ($item) {
            $itemKey = array_rand($item, 1);
            $value = $item[$itemKey];
            // this ensures me that i am distributing only unique numbers in the stripe
            // all 90 numbers will be present only once
            $this->items[$key] = $item;
            $this->keys[] = [$key, $itemKey];
        }

        return $value;
    }

    public function flushUsedItems()
    {
        foreach ($this->keys as $value) {
            $item = $this->items[$value[0]];
            unset($item[$value[1]]);
            $this->items[$value[0]] = $item;
        }
    }
}
