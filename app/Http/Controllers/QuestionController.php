<?php

namespace App\Http\Controllers;

use App\Models\Fizzy;
use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $request['fizzy'] = json_decode($request['fizzy']);

        $fizzy = Fizzy::select('id')->where('fizzy_token', $request->fizzyToken)->first();

        $question = Question::create([
            'question' => $request['fizzy']->question,
            'timer' => $request['fizzy']->timer,
            'points' => $request['fizzy']->points,
            'fizzy_id' => $fizzy->id,
        ]);


        foreach ($request['fizzy']->options as $option) {
            if ($option->choice) {
                QuestionChoice::create([
                    'choice' => $option->choice,
                    'is_choice_right' => $option->is_choice_right,
                    'question_id' => $question->id
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $request->file('image')->storeAs('public/que_cover/', $request->fizzyToken . "-" . $question->id . ".jpg");
        }

        return response()->json("Success", 200);
    }

    public function show(Request $request)
    {
        $fizzy = Fizzy::select('id')
            ->where([['fizzy_token', '=', $request['token']], ['user_id', '=', Auth::user()->id]])
            ->first();

        $question = Question::select('id', 'question', 'timer', 'points')
            ->where('fizzy_id', $fizzy->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $questionChoices = QuestionChoice::select('id', 'choice', 'is_choice_right')
            ->where('question_id', $question->id)
            ->get();

        $question['options'] = $questionChoices;

        $j = count($questionChoices);
        while ($j < 4) {
            $question['options'][$j] = ['choice' => "", 'is_choice_right' => false];
            $j++;
        }

        $question['imageUrl'] = Storage::exists("/public/que_cover/" . $request['token'] . "-" . $question->id . ".jpg");

        return response()->json($question, 200);
    }

    public function update(Request $request)
    {
        $request['question'] = json_decode($request['question']);
        // dd($request->all());

        Question::where('id', $request['question']->id)->update([
            'question' => $request['question']->question,
            'timer' => $request['question']->timer,
            'points' => $request['question']->points
        ]);

        foreach ($request['question']->options as $option) {
            if (isset($option->id)) {
                if ($option->choice) {
                    QuestionChoice::where('id', $option->id)->update([
                        'choice' => $option->choice,
                        'is_choice_right' => $option->is_choice_right
                    ]);
                } else {
                    QuestionChoice::find($option->id)->delete();
                }
            } else {
                QuestionChoice::create([
                    'choice' => $option->choice,
                    'is_choice_right' => $option->is_choice_right,
                    'question_id' => $request['question']->id
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $request->file('image')->storeAs('public/que_cover/', $request->fizzyToken . "-" . $request['question']->id . ".jpg");
        }

        return response()->json("Success", 200);
    }

    public function destroy($id, Request $request)
    {
        $imgPath = "public/que_cover/" . $request->token . "-" . $id . ".jpg";
        $questionImageExists = Storage::exists($imgPath);

        if ($questionImageExists) {
            Storage::delete($imgPath);
        }

        Question::where('id', $id)->delete();

        return response()->json('success', 200);
    }
}
