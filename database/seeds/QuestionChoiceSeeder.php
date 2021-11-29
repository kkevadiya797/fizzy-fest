<?php

use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return $this->createChoice();
    }

    private function createChoice()
    {
        for ($i=1; $i <= Question::count(); $i++) {
            $numnerOfChoice = mt_rand(2, 4);
            $choiceNumber = mt_rand(1, 4);

            while($numnerOfChoice < $choiceNumber) {
                $choiceNumber--;
            }

            for ($j=1; $j <= $numnerOfChoice; $j++) { 
                QuestionChoice::create([
                    'choice' => 'Option ' . $j,
                    'is_choice_right' => ($j == $choiceNumber ) ? 1 : 0,
                    'question_id' => $i
                ]);
            }
        }
    }
}