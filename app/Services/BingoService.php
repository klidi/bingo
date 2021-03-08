<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 10:23 PM
 */

namespace App\Services;


use App\Models\Cell;
use App\Models\Game;
use App\Models\Stripe;
use App\Services\Interfaces\BingoServiceInterface;

class BingoService implements BingoServiceInterface
{
    public function generateStripe(int $gameId, int $userId)
    {
        $stripe = new Stripe();
        $stripe->init($gameId, $userId);
        $stripe->save();
        $stripe->load('cards');
        return $stripe;
    }

    /**
     * here i will match and check the ball valuen   against all card cells for this game
     */
    public function checkMatchingCells(int $ball, Game $game) : void
    {
        // note , i am not using a query builder through relationships here
        Cell::check($game->id, $ball);
        $this->checkForSingleLineWin($game);
    }

    /**
     * i have put only this here. A sigle line win means round 1 is over.
     * there might be other better ways to do it but this was the second thing that came to my
     * mind
     */
    private function checkForSingleLineWin(Game $game)
    {
        $filledLines = Cell::filledLines($game->id);

        if ($filledLines->count() > 0) {
            $game->bingo();
        }
    }
}
