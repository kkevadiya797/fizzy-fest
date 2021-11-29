<?php

namespace App\Http\Controllers;

use App\models\Game;
use App\Models\Fizzy;
use App\models\Report;
use App\models\Question;
use App\Events\StartGame;
use App\Events\SendAnswer;
use App\models\Participant;
use App\Events\TimeoutEvent;
use Illuminate\Http\Request;
use App\Events\NewParticipate;
use App\models\QuestionChoice;
use App\Events\ScoreBoardEvent;
use App\Events\SendQuestionEvent;
use App\Events\AllResultCameEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function generatePin($fizzyKey)
    {

        $pin = rand(100000, 999999);

        $fizzy = Fizzy::select('id')->whereFizzy_token($fizzyKey)->first();

        $game = Game::create([
            'fizzy_id' => $fizzy->id,
            'pin' => $pin,
            'is_active' => true
        ]);

        $game = Game::select('id', 'pin')->where('id', $game->id)->get();
        return response()->json($game, 200);
    }

    public function checkPin($pin)
    {
        $enteredPin = $pin;
        $games = Game::where('is_active', '=', true)->get();
        $returnGame = [];
        foreach ($games as $game) {
            if ($enteredPin == $game->pin) {
                // return response()->json($game->started_at==null);
                if ($game->started_at == null) {
                    $returnGame = Game::select('id', 'pin')->where('id', $game->id)->get();
                    return response()->json($returnGame);
                } else {
                    return response()->json();
                }
            }
        }
    }

    public function setName(Request $request)
    {
        $game = $request->game;
        // dd($request->all());
        $participant = Participant::create([
            'game_id' => $game['id'],
            'name' => $request->name,
            'points' => 0
        ]);
        broadcast(new NewParticipate($game['id']));
        $participant = Participant::select('id', 'game_id', 'name', 'points', 'totalPoints')->where('id', '=', $participant->id)->get();

        return response()->json($participant);
    }

    public function startGame(Request $request)
    {

        broadcast(new StartGame($request->game, $request->showQuestion));

        $fizzyData = [];

        $fizzy = Fizzy::select('id', 'title')
            ->where('fizzy_token', $request->token)
            ->first();
        
        $player_count = Participant::where('game_id', $request->game)->count();
            
        $report = Report::create([
            'title' => $fizzy->title,
            'players' => $player_count,
            'game_id' => $request->game,
            'fizzy_id' => $fizzy->id,
            'user_id' => $request->userID
        ]);

        $fizzyQuestions = Question::select('id', 'question', 'timer')
            ->where('fizzy_id', $fizzy->id)
            ->orderBy('created_at')
            ->get();

        $hasImage = Storage::exists("/public/fizzy_cover/" . $request->token . ".jpg");
        $fizzyData = ['title' => $fizzy->title, 'hasImage' => $hasImage, 'questions' => $fizzyQuestions];

        $i = 0;
        foreach ($fizzyQuestions as $question) {
            $questionChoices = QuestionChoice::select('id', 'choice', 'is_choice_right')
                ->where('question_id', $question->id)
                ->get();
            $fizzyData['questions'][$i]['hasImage'] = Storage::exists("/public/que_cover/" . $request->token . "-" . $question->id . ".jpg");
            $fizzyData['questions'][$i]['choices'] = $questionChoices;
            $i++;
        }

        Game::where('id', $request->game)->update(['started_at' => date('Y-m-d H:i:s')]);
        return response()->json(['fizzy' => $fizzyData], 200);
    }

    public function get($gameId)
    {
        $participant = Participant::select('id', 'game_id', 'name', 'points', 'totalPoints')->where('game_id', '=', $gameId)->get();

        return response()->json($participant);
    }

    public function sendQuestion(Request $request)
    {
        $question = $request->question;
        // dd($question);
        broadcast(new SendQuestionEvent($question, $request->gameID));
        return response()->json($question);
    }

    public function sendAnswer(Request $request)
    {
        $participant = $request->participant;
        $answer = $request->answer;
        $question = $request->question;
        // dd($question['timer']);
        $timer = $request->timer;
        // dd($question['id']);
        $points = Question::select('points')->where('id', $request->question['id'])->first()->points;
        $participantes = Participant::where('id', '=', $participant)->get();

        if ($answer) {
            $generatedPoints = $this->generatePoints($points, $timer, $question);
            $totalPoints = $participantes[0]->totalPoints + $generatedPoints;
            Participant::where('id', '=', $participant)->update(['points' => $generatedPoints, 'totalPoints' => $totalPoints, 'curruntAnswer' => true]);
            $participantes = Participant::where('id', '=', $participant)->get();
            broadcast(new SendAnswer($answer, $participantes));
            return response()->json(['answer' => $answer, 'participant' => $participantes]);
        } else {
            Participant::where('id', '=', $participant)->update(['points' => '0', 'curruntAnswer' => true]);
            $participantes = Participant::where('id', '=', $participant)->get();
            broadcast(new SendAnswer($answer, $participantes));
            return response()->json(['answer' => $answer, 'participant' => $participantes]);
        }
        return response()->json();
    }

    public function generatePoints($point, $timer, $question)
    {
        $difference = $question['timer'] - $timer;
        if ($difference > 2) {
            $generatedPoints = round(($timer * $point) / $question['timer']);
            $minPoints = (30 * $point) / 100;
            if ($generatedPoints <= $minPoints) {
                return $minPoints;
            } else {
                return $generatedPoints;
            }
        } else {
            $generatedPoints = $point;
            return $generatedPoints;
        }
    }

    public function allResult(Request $request)
    {
        broadcast(new AllResultCameEvent($request->game));
        return response()->json();
    }

    public function timeout(Request $request)
    {
        broadcast(new TimeoutEvent($request->game));
        return response()->json();
    }

    public function changeStatus(Request $request)
    {
        Participant::where('game_id', '=', $request->game)->update(['curruntAnswer' => false]);
        return response()->json();
    }

    public function getRank(Request $request)
    {
        if($request->QuestionResult)
        {
            $participantes = Participant::where('game_id', '=', $request->gameID)->orderBy('points', 'desc')->get();
            return response()->json($participantes);
        }
        $participantes = Participant::where('game_id', '=', $request->gameID)->orderBy('totalPoints', 'desc')->get();
        if($request->participant)
        {
            $count = 1;
            foreach ($participantes as $participant) {
                if($participant->id == $request->participant)
                {
                    return response()->json($count);
                }
                $count += 1;
            }
        }
        broadcast(new ScoreBoardEvent($request->gameID));
        return response()->json($participantes);
    }
}
