<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 10:23 PM
 */

namespace App\Services\Interfaces;


use App\Models\Game;

interface BingoServiceInterface
{
    public function generateStripe(int $gameId, int $userId);
    public function checkMatchingCells(int $ball, Game $game) : void;
}
