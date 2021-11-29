<?php

use App\Models\Fizzy;
use App\Models\Game;
use App\Models\Participant;
use App\Models\Question;
use App\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (UserType::get()->count() == 0) {
            $this->call([
                UserTypeSeeder::class,
                RoleSeeder::class,
                UserSeeder::class
            ]);
        }

        $this->insertFactoryData([
            [User::class, 5],
            [Fizzy::class, 30],
            [Question::class, 100],
            [Game::class, 30],
            [Participant::class, 200]
        ]);

        $this->call([
            QuestionChoiceSeeder::class,
            ReportSeeder::class,
            ThemeSeeder::class
        ]);
    }

    private function insertFactoryData($factories)
    {
        foreach ($factories as $factory) {
            Factory($factory[0], $factory[1])->create($factory[3] ?? []);
        }
        return;
    }
}
