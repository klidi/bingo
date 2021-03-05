<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 2.3.21
 * Time: 9:55 AM
 */

namespace App\Models\Collections;


use Illuminate\Support\Collection;

class Cards extends Collection
{
    public function addTyped(Ticket $ticket) : void
    {
        $this->add($ticket);
    }
}
