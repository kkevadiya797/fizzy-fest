<?php

use App\Models\Fizzy;
use App\Models\Game;
use App\Models\Participant;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= Game::count(); $i++) {
            $fizzyId = Game::where('id', $i)->value('fizzy_id');
            $userId = Fizzy::where('id',$fizzyId)->value('user_id');

            Report::create([
                'title' => Fizzy::where('id', $fizzyId)->value('title'),
                'players' => Participant::where('game_id', $i)->count(),
                'game_id' => $i,
                'fizzy_id' => $fizzyId,
                'user_id' => $userId,
                'created_at' => Game::where('id', $i)->value('created_at')
            ]);
        }
    }
}
