<?php
/**
 * Created by IntelliJ IDEA.
 * User: iraklid
 * Date: 4.3.21
 * Time: 6:59 PM
 */

namespace App\Console\Commands;


use App\Console\Commands\Traits\AskWisely;
use App\Services\Interfaces\GameServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StartGame extends Command
{
    use AskWisely;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start a bingo game :P ';


    private GameServiceInterface $gameService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GameServiceInterface $gameService)
    {
        Log::info('This is just a dummy game case for fun');
        $this->gameService = $gameService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $question = "Please input a game ID";
        $id = $this->askValid($question, 'id', ['exists:games']);

        $this->gameService->start($id);

        // this is one for one round, single line.
        while (!$this->gameService->didSomeoneSayBINGO()) {
            $ball = $this->gameService->drawBall();
            $this->info($ball);
        }

        $this->warn("BINGO!!! BINGO!!! BINGO!!");
        $result = $this->gameService->getGameResults();
        $this->info("uncomment line 71 to see the full winning cards and bingo number");
        //$this->info(json_encode($result));
    }
}
