<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Csv\Reader;

class CSVController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('csv_file');

        // Read the CSV file
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        // Get the CSV data
        $data = $csv->getRecords();

        // Save the data to the database
        foreach ($data as $row) {
            Weather::insert([
                'year' => $row['YEAR'],
                'month' => $row['MONTH'],
                'day' => $row['DAY'],
                'rainfall' => $row['RAINFALL'],
                'temperature_max' => $row['TMAX'],
                'temperature_min' => $row['TMIN'],
                'temperature_mean' => $row['TMEAN'],
                'wind_speed' => $row['WIND_SPEED'],
                'wind_direction' => $row['WIND_DIRECTION'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Redirect or show success message
        return redirect()->back()->with('success', 'CSV data has been saved to the database.');
    }
}
