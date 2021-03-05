<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 9:57 PM
 */

namespace App\Services\Interfaces;


interface GameServiceInterface
{
    public function start(int $id) : void;
    public function didSomeoneSayBINGO() : bool;
    public function drawBall() : int;
    public function getGameResults() : array;
}
