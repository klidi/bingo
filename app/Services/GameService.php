<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 1.3.21
 * Time: 9:56 PM
 */

namespace App\Services;


use App\Services\Interfaces\BingoServiceInterface;
use App\Services\Interfaces\GameServiceInterface;
use App\Models\Game;

class GameService implements GameServiceInterface
{
    private BingoServiceInterface $bingoService;
    private Game $game;

    public function __construct(BingoServiceInterface $bingoService)
    {
        $this->bingoService = $bingoService;
    }

    public function start(int $id) : void
    {
        $this->game = Game::findOrFail($id);
    }

    public function didSomeoneSayBINGO() : bool
    {
        return $this->game->isBingo();
    }

    public function drawBall() : int // in real situation this would be value object Ball
    {
        $this->gameStarted();

        $ball = $this->game->nextBall();

        $this->bingoService->checkMatchingCells($ball, $this->game);

        return $ball;
    }

    /**
     * read comment in game->terminate()
     */
    public function getGameResults() : array
    {
        return $this->game->terminate();
    }

    /**
     * @throws \Exception
     */
    private function gameStarted() : void
    {
        if (!isset($this->game)) {
            throw new \Exception("Must start the game first");
        }
    }
}
