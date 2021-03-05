<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 7:42 PM
 */

namespace App\Models\Contracts;


interface ValuePoolGenerator
{
    public function generatePool(): void;
}
