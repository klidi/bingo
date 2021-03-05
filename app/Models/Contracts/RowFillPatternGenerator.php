<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 1:19 PM
 */

namespace App\Models\Contracts;


interface RowFillPatternGenerator
{
    public function generateRowFillPatterns() : void;
}
