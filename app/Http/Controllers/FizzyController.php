<?php

namespace App\Http\Controllers;

use App\Models\Fizzy;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FizzyController extends Controller
{
    public function index()
    {
        $fizzyData = [];
        $fizzies = User::find(Auth::user()->id)->fizzies()->selectRaw('title, description,type, fizzy_token,created_at')->orderBy('created_at', 'desc')->get();
        $fizzyData = $fizzies;

        $fizzyIds = User::find(Auth::user()->id)->fizzies()->selectRaw('id, fizzy_token,publish_at')->orderBy('created_at', 'desc')->get();
        foreach ($fizzyIds as $key => $fizzy) {
            Arr::add($fizzyData[$key], 'total_plays', Fizzy::find($fizzy->id)->reports()->withTrashed()->count());
            Arr::add($fizzyData[$key], 'total_players', Fizzy::find($fizzy->id)->reports()->withTrashed()->sum('players'));
            Arr::add($fizzyData[$key], 'hasImage', Storage::exists("/public/fizzy_cover/" . $fizzy->fizzy_token . ".jpg"));
            Arr::add($fizzyData[$key], 'no_of_questions', Question::where('fizzy_id', $fizzy->id)->count());
            Arr::add($fizzyData[$key], 'is_published', (($fizzy->publish_at != null) ? true : false));
        }

        return response()->json(['fizzy' => $fizzyData], 200);
    }

    public function type()
    {
        $fizzyTypes = User::find(Auth::user()->id)->fizzies()->selectRaw('type')->orderBy('type')->groupBy('type')->get();
        $type = [];
        $i = 0;
        foreach ($fizzyTypes as $fizzyType) {
            $type[$i] = $fizzyType->type;
            $i++;
        }
        return response()->json(['type' => $type], 200);
    }

    public function show($token)
    {
        $fizzyData = [];
        $fizzy = User::find(Auth::user()->id)->fizzies()->selectRaw('id, title, description,publish_at')->where('fizzy_token', $token);


        if ($fizzy->doesntExist()) {
            return response()->json('Page not found', 404);
        }

        $fizzy = $fizzy->first();
        $fizzyQuestions = Question::select('id', 'question', 'timer', 'points')
            ->where('fizzy_id', $fizzy->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPlays = Fizzy::find($fizzy->id)->reports->count();
        $totalPlayers = Fizzy::find($fizzy->id)->reports->sum('players');

        $fizzyData = ['title' => $fizzy->title, 'description' => $fizzy->description, 'publish_at' => $fizzy->publish_at, 'total_plays' => $totalPlays, 'total_players' => $totalPlayers, 'questions' => $fizzyQuestions];


        $i = 0;
        foreach ($fizzyQuestions as $question) {
            $questionChoices = QuestionChoice::select('id', 'choice', 'is_choice_right')
                ->where('question_id', $question->id)
                ->orderBy('id')
                ->get();

            $fizzyData['questions'][$i]['options'] = $questionChoices;

            $j = count($questionChoices);
            while ($j < 4) {
                $fizzyData['questions'][$i]['options'][$j] = ['choice' => "", 'is_choice_right' => false];
                $j++;
            }

            $fizzyData['questions'][$i]['imageUrl'] = Storage::exists("/public/que_cover/" . $token . "-" . $question->id . ".jpg");

            $i++;
        }

        $fizzyData['hasImage'] = Storage::exists("/public/fizzy_cover/" . $token . ".jpg");

        return response()->json(['fizzy' => $fizzyData], 200);
    }

    public function store(Request $request)
    {
        $fizzyToken = Str::uuid(32);

        Fizzy::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'fizzy_token' => $fizzyToken,
            'publish_at' => (Auth::user()->role_id === 1) ? null : now(),
            'user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('image')) {
            $request->file('image')->storeAs('public/fizzy_cover', $fizzyToken . ".jpg");
        }

        return response()->json(['token' => $fizzyToken], 200);
    }

    public function delete(Request $request)
    {
        if ($request->selectedAllFizzyToken == ![]) {
            foreach ($request->selectedAllFizzyToken as $fizzyToken) {
                $selectedFizzyId = Fizzy::select('id')->where('fizzy_token', '=', $fizzyToken)->first();
                $selectedFizzyQuestions = Question::select('id')->where('fizzy_id', '=', $selectedFizzyId->id)->get();

                foreach ($selectedFizzyQuestions as $selectedFizzyQuestion) {
                    $fizzyQuestionImg = Storage::exists("public/que_cover/" . $fizzyToken . "-" . $selectedFizzyQuestion->id . ".jpg");
                    if ($fizzyQuestionImg) {
                        Storage::delete("/public/que_cover/" . $fizzyToken . "-" . $selectedFizzyQuestion->id . ".jpg");
                    }
                }
                Fizzy::where([['fizzy_token', '=', $fizzyToken], ['user_id', '=', Auth::user()->id]])->delete();
                $fizzyImg = Storage::exists("/public/fizzy_cover/" . $fizzyToken . ".jpg");
                if ($fizzyImg) {
                    Storage::delete("/public/fizzy_cover/" . $fizzyToken . ".jpg");
                }
            }
            return response()->json('selected succesfully deleted', 200);
        } else {
            $fizzyId = Fizzy::select('id')->where('fizzy_token', '=', $request->selectedFizzyToken)->first()->id;
            $fizzyQuestions = Question::select('id')->where('fizzy_id', '=', $fizzyId)->get();

            foreach ($fizzyQuestions as $fizzyQuestion) {
                $fizzyQuestionImg = Storage::exists("public/que_cover/" . $request->selectedFizzyToken . "-" . $fizzyQuestion->id . ".jpg");
                if ($fizzyQuestionImg) {
                    Storage::delete("/public/que_cover/" . $request->selectedFizzyToken . "-" . $fizzyQuestion->id . ".jpg");
                }
            }

            Fizzy::find($fizzyId)->delete();

            $fizzyImg = Storage::exists("/public/fizzy_cover/" . $request->selectedFizzyToken . ".jpg");
            if ($fizzyImg) {
                Storage::delete("/public/fizzy_cover/" . $request->selectedFizzyToken . ".jpg");
            }
            return response()->json('succesfully deleted', 200);
        }
    }

    public function edit(Request $request)
    {
        Fizzy::select('id', 'title')->where([['fizzy_token', '=', $request->token], ['user_id', '=', Auth::user()->id]])->update(['title' => $request->title, 'description' => $request->description]);
        if ($request->image != "null") {
            $fizzyImg = Storage::exists("/public/fizzy_cover/" . $request->token . ".jpg");
            if ($fizzyImg) {
                Storage::delete("/public/fizzy_cover/" . $request->token . ".jpg");
            }
            if ($request->hasFile('image')) {
                $request->file('image')->storeAs('public/fizzy_cover', $request->token . ".jpg");
            }
        }
        return response()->json('successfully updated', 200);
    }

    public function popularFizzies($user = 1)
    {
        $fizzies = User::find($user)->fizzies()
            ->where('publish_at', '!=', null)
            ->join('reports', 'fizzies.id', '=', 'reports.fizzy_id')
            ->selectRaw('fizzies.title, fizzies.fizzy_token, fizzies.description, count(*) as plays, sum(players) as players, fizzies.id')
            ->orderBy('plays', 'desc')
            ->take(10)
            ->groupBy(['fizzies.title', 'fizzies.fizzy_token', 'fizzies.id'])
            ->get();

        foreach ($fizzies as $key => $fizzy) {
            $noOfQuestions = Question::selectRaw('count(*)')->where('fizzy_id', $fizzy->id)->count();

            Arr::add($fizzy, 'no_of_questions', $noOfQuestions);
        }

        return response()->json($fizzies, 200);
    }

    public function latestFizzies($user = 1)
    {
        $fizzies = User::find($user)->fizzies()
            ->where('publish_at', '!=', null)
            ->join('reports', 'fizzies.id', '=', 'reports.fizzy_id')
            ->selectRaw('fizzies.title, fizzies.fizzy_token, fizzies.description, count(*) as plays, sum(players) as players, fizzies.id')
            ->orderBy('fizzies.created_at', 'desc')
            ->take(10)
            ->groupBy(['fizzies.title', 'fizzies.fizzy_token', 'fizzies.id'])
            ->get();
        
        return response()->json($fizzies, 200);
    }

    public function discover()
    {
        $user = 1;

        $fizzyTypes = User::find($user)->fizzies()->where('publish_at', '!=', null)->selectRaw('type')->orderBy('type')->groupBy('type')->get();
        $types = [];
        $i = 0;
        foreach ($fizzyTypes as $fizzyType) {
            $types[$i] = $fizzyType->type;
            $i++;
        }

        $fizzies = [];
        $fizzies['Latest'] = $this->latestFizzies($user)->original;
        $fizzies['Top picks'] = $this->popularFizzies($user)->original;


        foreach ($types as $type) {

            $fizzies[$type] = User::find($user)->fizzies()
                ->where([['type', '=', $type],['publish_at', '!=', null]])
                ->join('reports', 'fizzies.id', '=', 'reports.fizzy_id')
                ->selectRaw('fizzies.title, fizzies.fizzy_token, fizzies.description, count(*) as plays, sum(players) as players, fizzies.id')
                ->orderBy('fizzies.created_at', 'desc')
                ->take(10)
                ->groupBy(['fizzies.title', 'fizzies.fizzy_token', 'fizzies.id'])
                ->get();
        }

        foreach ($fizzies as $key => $fizzys) {
            foreach ($fizzys as $key => $fizzy) {
                $noOfQuestions = Question::selectRaw('count(*)')->where('fizzy_id', $fizzy->id)->count();
                Arr::add($fizzy, 'no_of_questions', $noOfQuestions);
            }
        }

        return response()->json($fizzies, 200);
    }

    public function discoverShow($token)
    {
        $fizzyData = [];
        $fizzy = User::find(1)->fizzies()->selectRaw('id, title, description')->where('fizzy_token', $token);


        if ($fizzy->doesntExist()) {
            return response()->json('Page not found', 404);
        }

        $fizzy = $fizzy->first();
        $fizzyQuestions = Question::select('id', 'question', 'timer', 'points')
            ->where('fizzy_id', $fizzy->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPlays = Fizzy::find($fizzy->id)->reports->count();
        $totalPlayers = Fizzy::find($fizzy->id)->reports->sum('players');

        $fizzyData = ['title' => $fizzy->title, 'description' => $fizzy->description, 'total_plays' => $totalPlays, 'total_players' => $totalPlayers, 'questions' => $fizzyQuestions];


        $i = 0;
        foreach ($fizzyQuestions as $question) {
            $questionChoices = QuestionChoice::select('id', 'choice', 'is_choice_right')
                ->where('question_id', $question->id)
                ->orderBy('id')
                ->get();

            $fizzyData['questions'][$i]['options'] = $questionChoices;

            $j = count($questionChoices);
            while ($j < 4) {
                $fizzyData['questions'][$i]['options'][$j] = ['choice' => "", 'is_choice_right' => false];
                $j++;
            }

            $fizzyData['questions'][$i]['imageUrl'] = Storage::exists("/public/que_cover/" . $token . "-" . $question->id . ".jpg");

            $i++;
        }

        $fizzyData['hasImage'] = Storage::exists("/public/fizzy_cover/" . $token . ".jpg");

        return response()->json(['fizzy' => $fizzyData], 200);
    }

    public function statusEdit(Request $request)
    {
        $newStatus = ($request->status) ? now() : null;

        Fizzy::where([
            ['user_id', '=', Auth::user()->id],
            ['fizzy_token', '=', $request->fizzyToken]
        ])
            ->update(['publish_at' => $newStatus]);

        return response()->json('succesfully updated', 200);
    }

    public function imgDelete(Request $request)
    {
        if ($request->imgUrl) {
            $img = Storage::exists("/public/que_cover/" . $request->imgUrl . ".jpg");
            if ($img) {
                Storage::delete("/public/que_cover/" . $request->imgUrl . ".jpg");
            }
        } else {
            $img = Storage::exists("/public/fizzy_cover/" . $request->token . ".jpg");
            if ($img) {
                Storage::delete("/public/fizzy_cover/" . $request->token . ".jpg");
            }
        }


        return response()->json('successfully image deleted', 200);
    }
}
