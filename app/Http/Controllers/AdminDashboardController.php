<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $totalCount = [];

        $users = User::where('role_id', '!=', 1);
        $userReports = new Report;

        $totalCount['totalUsers'] = $users->count();
        $totalCount['totalPlays'] = $userReports->withTrashed()->count();
        $totalCount['totalPlayers'] = $userReports->withTrashed()->sum('players');

        // $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');

        $userMonthlyFizzies = (clone $users)->where("created_at", ">=", $monthStartDate);
        $userMonthlyReports = (clone $userReports)->where("reports.created_at", ">=", $monthStartDate);

        $monthlyCount = [];
        $monthlyCount['totalUsers'] = $userMonthlyFizzies->count();
        $monthlyCount['totalPlays'] = $userMonthlyReports->withTrashed()->count();
        $monthlyCount['totalPlayers'] = $userMonthlyReports->withTrashed()->sum('players');

        $chartData = [];
        $chartData['users'] = $this->countMonthlyRecords($userMonthlyFizzies);
        $chartData['plays'] = $this->countMonthlyRecords($userMonthlyReports);
        $chartData['players'] = $this->countMonthlyRecords($userMonthlyReports, 'players');

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
                if ($query) {
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
}
