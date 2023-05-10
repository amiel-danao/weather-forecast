@extends('layouts.app', ['class' => 'login-page', 'page' => __('Login Page'), 'contentClass' => 'login-page'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mx-auto my-auto" style="width: 50% !important;">
                <div class="card-header">
                    <h3 class="font-weight-bold">Subscribe</h3>
                </div>
                <div class="card-body">
                  <form>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Full Name</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Full Name">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Contact Number</label>
                      <input type="number" class="form-control" id="exampleInputPassword1" placeholder="Contact Number">
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-default">Cancel</button>
                            <button  class="btn btn-primary">Subscribe</button>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
@endsection