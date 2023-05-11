@extends('layouts.app', ['class' => 'login-page', 'page' => __('Login Page'), 'contentClass' => 'login-page'])

@section('content')
    <div class="row d-flex">
        <div class="col-lg-2 col-md-6 ml-auto mr-auto pl-0 pr-1">
            <form class="form" method="post" action="{{ route('login') }}">
                @csrf
    
                <div class="card card-login card-white" style="height: 619px">
                    <div class="card-body" height="100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="font-weight-bold" style="color: black;">Port Area, Manila</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{{ asset('weather-assets') }}/partly-cloudy-day.svg">
                                <h4 class="font-weight-bold text-center" style="color: black;">Partly Cloud</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center" style="color: black;">{{ isset($getDataForToday->temperature_mean) ? $getDataForToday->temperature_mean : "No Data Available" }} C</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{-- <h5 class="text-center font-weight-bold" style="color: black;">{{ strftime('%A, %B %e, %Y %I:%M:%S %p', now()->timestamp) }}</h5> --}}
                                <h5 class="text-center font-weight-bold" style="color: black;">{{ $getPastDate }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-7 col-md-6 pl-1 pr-1">
            <div class="card card-login card-white mb-2" style="height: 305px;">
                <div class="card-body" height="100">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="font-weight-bold mb-2" style="color: black;">Today's Forecast</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="font-weight-bold text-center mb-2" style="color: black;">Wind</h4>
                            <span class="d-flex justify-content-center">
                                <img src="{{ asset('weather-assets') }}/wind-beaufort-2.svg" height="70" width="100">
                                <p class="font-weight-bold my-auto" style="color: black; vertical-align: middle;">{{ isset($getDataForToday->wind_speed) ? $getDataForToday->wind_speed : "No Data Available" }} km/h</p>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h4 class="font-weight-bold text-center mb-2" style="color: black;">Rainfall</h4>
                            <span class="d-flex justify-content-center">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="font-weight-bold my-auto" style="color: black;">{{ isset($getDataForToday->rainfall) ? $getDataForToday->rainfall : "No Data Available" }} %</p>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="font-weight-bold text-center mb-2" style="color: black;">Temperature</h4>
                            <span class="d-flex justify-content-center">
                                <img src="{{ asset('weather-assets') }}/thermometer-celsius.svg" height="70" width="100">
                                <p class="font-weight-bold my-auto" style="color: black; vertical-align: middle;">{{ isset($getDataForToday->temperature_mean) ? $getDataForToday->temperature_mean : "No Data Available" }} degrees celsius</p>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h4 class="font-weight-bold text-center mb-2" style="color: black;">Wind Direction</h4>
                            <span class="d-flex justify-content-center">
                                <img src="{{ asset('weather-assets') }}/windsock.svg" height="70" width="100">
                                @if ( (isset($getDataForToday->wind_direction) ? $getDataForToday->wind_direction : "") != "" )
                                    @if($getDataForToday->wind_direction >= 337.5 || $getDataForToday->wind_direction < 22.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">North</p>
                                    @elseif($getDataForToday->wind_direction >= 22.5 || $getDataForToday->wind_direction < 67.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">Northeast</p>
                                    @elseif($getDataForToday->wind_direction >= 67.5 || $getDataForToday->wind_direction < 112.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">East</p>
                                    @elseif($getDataForToday->wind_direction >= 112.5 || $getDataForToday->wind_direction < 157.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">Southeast</p>
                                    @elseif($getDataForToday->wind_direction >= 157.5 || $getDataForToday->wind_direction < 202.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">South</p>
                                    @elseif($getDataForToday->wind_direction >= 202.5 || $getDataForToday->wind_direction < 247.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">Southwest</p>
                                    @elseif($getDataForToday->wind_direction >= 247.5 || $getDataForToday->wind_direction < 292.5)
                                        <p class="font-weight-bold my-auto" style="color: black;">West</p>
                                    @else
                                        <p class="font-weight-bold my-auto" style="color: black;">Northwest</p>
                                    @endif
                                @else
                                    <p class="font-weight-bold my-auto" style="color: black !important;">No Data Available</p>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-login card-white" style="height: 305px;">
                <div class="card-body" height="100">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center font-weight-bold" style="color: black;">7 Day Forecast</h4>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-around pl-0 pr-0">
                        @forelse ($getSevenDaysWeatherForecast as $weatherForecast)
                            @php
                                $carbonDate = \Carbon\Carbon::parse("{$weatherForecast->year}-{$weatherForecast->month}-{$weatherForecast->day}");
                                $formattedDate = $carbonDate->format('l');
                            @endphp
                            <div class="card" style="background-color: transparent;">
                                <div class="card-body">
                                    <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                    <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">{{ $formattedDate }}</p>
                                    <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                    <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">{{ $weatherForecast->rainfall }}%</p>
                                </div>
                            </div>
                        @empty
                            <h5 class="font-weight-bold text-center" style="color: black;">No Data Available</h5>
                        @endforelse
                        {{-- <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Sunday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Monday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Tuesday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Wednesday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Thursday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Friday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div>
                        <div class="card" style="background-color: transparent;">
                            <div class="card-body">
                                <img src="{{ asset('weather-assets') }}/rain.svg" height="70" width="100">
                                <p class="text-center font-weight-bold" style="color: black; font-size: 9px;">Saturday</p>
                                <p class="text-center font-weight-bold mt-4" style="color: black; font-size: 10px;">Chances of rainfall</p>
                                <p class="text-center font-weight-bold mt-2" style="color: black; font-size: 10px;">0.5%</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 ml-auto mr-auto pl-1 pr-0">
            {{-- <form class="form" method="post" action="{{ route('login') }}">
                @csrf --}}
    
                <div class="card card-login card-white"  style="height: 619px">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" method="post" action="{{ route('login') }}">
                                @csrf
                                <div class="card-body" height="100">
                                    <p class="text-dark mb-2 text-center">Log In</p>
                                    <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tim-icons icon-email-85"></i>
                                            </div>
                                        </div>
                                        <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}">
                                        @include('alerts.feedback', ['field' => 'email'])
                                    </div>
                                    <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tim-icons icon-lock-circle"></i>
                                            </div>
                                        </div>
                                        <input type="password" placeholder="{{ __('Password') }}" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                        @include('alerts.feedback', ['field' => 'password'])
                                    </div>
                                    <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">{{ __('Login') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <a href="{{ route('subscribe') }}" class="btn btn-primary btn-lg btn-block mb-3 text-center data-bs-toggle="modal" data-bs-target="#exampleModal"">{{ __('Subscribe') }}</a>
                                {{-- <div class="pull-left">
                                    <h6>
                                        <a href="{{ route('register') }}" class="link footer-link">{{ __('Create Account') }}</a>
                                    </h6>
                                </div>
                                <div class="pull-right">
                                    <h6>
                                        <a href="{{ route('password.request') }}" class="link footer-link">{{ __('Forgot password?') }}</a>
                                    </h6>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="card-footer">
                        <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">{{ __('Login') }}</button>
                        <div class="pull-left">
                            <h6>
                                <a href="{{ route('register') }}" class="link footer-link">{{ __('Create Account') }}</a>
                            </h6>
                        </div>
                        <div class="pull-right">
                            <h6>
                                <a href="{{ route('password.request') }}" class="link footer-link">{{ __('Forgot password?') }}</a>
                            </h6>
                        </div>
                    </div> --}}
                </div>
            {{-- </form> --}}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
@endsection
