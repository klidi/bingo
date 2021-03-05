<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 4.3.21
 * Time: 3:51 PM
 */

namespace App\Http\Controllers;

use App\Services\Interfaces\BingoServiceInterface;
use App\Models\Game;
use Illuminate\Http\Request;

class StripeController
{
    private BingoServiceInterface $bingoService;

    public function __construct(BingoServiceInterface $bingoService)
    {
        $this->bingoService = $bingoService;
    }

    public function createStripe(Request $request, int $gameId, int $userId)
    {
        // due to time im making this simple validations.
        // checking that game exists and that user is partecipating in the game
        $game = Game::findOrFail(1);
        $game->users()->findOrFail($userId);

        return $this->bingoService->generateStripe($gameId, $userId);
    }
}
