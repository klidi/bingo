<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 12:33 PM
 */

namespace App\Models\Traits;


trait IsValidRowFillPattern
{
    /**
     * This method validates if the permutation is valid based on 2 conditions
     * 1. there is 5 true/1 values. | 2. There is no more then 2 consecutive gaps.
     * @param $permutation
     * @return bool
     */
    private function isValid($pattern) : bool
    {
        $repeat = 0;     // Number of consecutive falses (gaps)
        $count = 0;      // Number of trues (numbers)
        $last = false;
        // this needs refactoring
        for ($cell = 0; $cell < 9; $cell++) {
            $current = $pattern[$cell];

            if ($current == $last) {
                if (++$repeat > 2)
                    return false; // May not have more than 2 consecutive gaps
            } else {
                $repeat = 1;
            }

            if ($current) {
                $count++;
            }

            $last = $current;
        }
        return $count == 5;
    }
}
