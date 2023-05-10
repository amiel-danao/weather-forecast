<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Display icons page
     *
     * @return \Illuminate\View\View
     */
    public function icons()
    {
        return view('pages.icons');
    }

    /**
     * Display maps page
     *
     * @return \Illuminate\View\View
     */
    public function maps()
    {
        return view('pages.maps');
    }

    /**
     * Display tables page
     *
     * @return \Illuminate\View\View
     */
    public function tables()
    {
        return view('pages.tables');
    }

    /**
     * Display notifications page
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('pages.notifications');
    }

    /**
     * Display rtl page
     *
     * @return \Illuminate\View\View
     */
    public function rtl()
    {
        return view('pages.rtl');
    }

    /**
     * Display typography page
     *
     * @return \Illuminate\View\View
     */
    public function typography()
    {
        return view('pages.typography');
    }

    /**
     * Display upgrade page
     *
     * @return \Illuminate\View\View
     */
    public function upgrade()
    {
        return view('pages.upgrade');
    }

    public function accounts()
    {
        return view('pages.accounts');
    }

    public function weatherUpdates()
    {
        $data = Weather::paginate(15);
        
        $twoYearsAgo = Carbon::now()->subYears(3);
        $sameDateToday = Carbon::now()->setYear($twoYearsAgo->year);

        // Format the date as desired
        $formattedDate = $sameDateToday->format('Y-m-d');
        $formattedDay = $sameDateToday->format('l');
        // dd(explode('-', $formattedDate), $formattedDay);

        return view('pages.weather-updates', compact('data'));
    }
}
