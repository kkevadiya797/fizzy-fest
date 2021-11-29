<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Participant;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $report = Report::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
        return response()->json(['report' => $report], 200);
    }

    public function show($id)
    {
        $report = Report::select('game_id', 'title', 'created_at')->where('id', $id)->first();
        $participants = Participant::select('name', 'totalPoints')->where('game_id', $report->game_id)->orderBy('totalPoints', 'desc')->get();
        $i = 1;
        foreach ($participants as $participant) {
            Arr::add($participant, 'rank', $i);
            $i++;
        }
        
        return response()->json(['report' => $participants, 'reportTitle' => $report->title, 'hostName' => Auth::user()->name, 'created_at' => $report->created_at->format('d-M-Y')], 200);
    }

    public function delete(Request $request)
    {
        if ($request->selectedAllReportId == ![]) {
            foreach ($request->selectedAllReportId as $reportId) {
                $game = Report::where('id', $reportId)->first();

                Game::where('id', $game->game_id)->delete();
                Report::where('id', $reportId)->delete();
            }

            return response()->json('all report deleted successfully', 200);
        } else {
            $game = Report::where('id', $request->reportId)->first();

            Game::where('id', $game->game_id)->delete();
            Report::where('id', $request->reportId)->delete();

            return response()->json('report deleted succesfully', 200);
        }
    }
}
