<?php

namespace App\Http\Controllers;

use App\Models\Fizzy;
use App\Models\Question;
use App\Models\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $totalCount = [];

        $userFizzies = User::find($user)->fizzies();
        $userReports = Report::where('user_id', Auth::user()->id);

        $totalCount['totalFizzies'] = $userFizzies->count();
        $totalCount['totalPlays'] = $userReports->withTrashed()->count();
        $totalCount['totalPlayers'] = $userReports->withTrashed()->sum('players');
        $totalCount['totalReoprts'] = (clone $userReports)->where('deleted_at', null)->count();

        // $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');

        $userMonthlyFizzies = (clone $userFizzies)->where("created_at", ">=", $monthStartDate);
        $userMonthlyReports = (clone $userReports)->where("reports.created_at", ">=", $monthStartDate);

        $monthlyCount = [];
        $monthlyCount['totalFizzies'] = $userMonthlyFizzies->count();
        $monthlyCount['totalPlays'] = $userMonthlyReports->withTrashed()->count();
        $monthlyCount['totalPlayers'] = $userMonthlyReports->withTrashed()->sum('players');
        $monthlyCount['totalReoprts'] = (clone $userMonthlyReports)->where('deleted_at', null)->count();

        $chartData = [];
        $chartData['fizzies'] = $this->countMonthlyRecords($userMonthlyFizzies);
        $chartData['plays'] = $this->countMonthlyRecords($userMonthlyReports);
        $chartData['players'] = $this->countMonthlyRecords($userMonthlyReports, 'players');
        $chartData['reports'] = $this->countMonthlyRecords($userMonthlyReports, 'onlyActive');

        return response()->json(['cardData' => $totalCount, 'monthlyCount' => $monthlyCount, 'chartData' => $chartData], 200);
    }

    public function countMonthlyRecords($tableData, $query = '')
    {
        $daysInMonth = Carbon::now()->daysInMonth;

        $data = $tableData->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d');
        });

        $monthlyData = [];

        for ($i = 0; $i < $daysInMonth; $i++) {

            $key = (($i < 9) ? "0" : "") . ($i + 1);
            if (array_key_exists($key, $data->toArray())) {
                if ($query == 'onlyActive') {
                    $monthlyData[$i] = $data[$key]->where('deleted_at', null)->count();
                } else if ($query) {
                    $monthlyData[$i] = $data[$key]->sum($query);
                } else {
                    $monthlyData[$i] = $data[$key]->count();
                }
            } else {
                $monthlyData[$i] = 0;
            }
        }

        return $monthlyData;
    }

    public function fizzy()
    {
        $fizzyData = [];
        $fizzies = User::find(Auth::user()->id)->fizzies()->selectRaw('title, description, created_at')->orderBy('created_at', 'desc')->take(5)->get();
        $fizzyData = $fizzies;

        $fizzyIds = User::find(Auth::user()->id)->fizzies()->selectRaw('id')->orderBy('created_at', 'desc')->take(5)->get();
        
        foreach ($fizzyIds as $key => $fizzy) {
            Arr::add($fizzyData[$key], 'no_of_questions', Question::where('fizzy_id', $fizzy->id)->count());
        }

        return response()->json(['fizzy' => $fizzyData], 200);
    }

    public function report()
    {
        $report = Report::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->take(5)->get();

        return response()->json(['report' => $report], 200);
    }
}
