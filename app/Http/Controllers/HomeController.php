<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Weather;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $sameDateToday = Carbon::now();//->setYear($threeYearsAgo->year);

        $formattedDate = $sameDateToday->format('Y-m-d');
        $explodedDate = explode('-', $formattedDate);

        $getDataForToday = Weather::where('year', intval($explodedDate[0]))->where('month', intval($explodedDate[1]))->where('day', intval($explodedDate[2]))->first();
        
        $getSevenDaysWeatherForecast = Weather::where('year', intval($explodedDate[0]))->where('month', intval($explodedDate[1]))->whereBetween('day', [intval($explodedDate[2]), intval($explodedDate[2]) + 6])->get();
        return view('dashboard', compact('getDataForToday', 'getSevenDaysWeatherForecast'));
    }
}
