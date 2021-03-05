<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameUser;
use App\Models\User;
use App\Services\Interfaces\BingoServiceInterface;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private BingoServiceInterface $bingoService;

    public function __construct(BingoServiceInterface $bingoService)
    {
        $this->bingoService = $bingoService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // just some seeding dummy data. im putting here just for saving time
        $game = new Game([]);
        $game->save();
        // this will take a bit. max 2-3min
        for ($i = 0; $i <= 1000; $i++) {
            $user = new User([]);
            $user->save();
            $game->users()->attach($user->id);
            $this->bingoService->generateStripe($game->id, $user->id);
        }
    }
}
