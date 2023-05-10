@extends('layouts.app', ['page' => __('Weather Updates'), 'pageSlug' => 'weather-updates'])

@section('content')
    <div class="row mt-5">
        <div class="col-md-12 text-right">
            {{-- <button type="button" class="btn btn-primary">Upload CSV</button> --}}
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Upload CSV
            </button>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold">Weather Updates</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th> Year </th>
                                    <th> Month </th>
                                    <th> Day </th>
                                    <th> Rainfall </th>
                                    <th> Temperature (Min) </th>
                                    <th> Temperature (Max) </th>
                                    <th> Temperature (Mean) </th>
                                    <th> Wind Speed </th>
                                    <th> Wind Direction </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr>
                                        <td class="text-center">{{ $row->year }}</td>
                                        <td class="text-center">{{ $row->month }}</td>
                                        <td class="text-center">{{ $row->day }}</td>
                                        <td class="text-center">{{ $row->rainfall }}</td>
                                        <td class="text-center">{{ $row->temperature_min }}</td>
                                        <td class="text-center">{{ $row->temperature_max }}</td>
                                        <td class="text-center">{{ $row->temperature_mean }}</td>
                                        <td class="text-center">{{ $row->wind_speed }}</td>
                                        <td class="text-center">{{ $row->wind_direction }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Data Available</td>
                                    </tr>   
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ $data->links('components.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">UPLOAD CSV FILE</h5>
                {{-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="file" name="csv_file">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
        </div>
    </div>
@endsection