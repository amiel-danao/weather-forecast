@extends('layouts.app', ['page' => __('Accounts'), 'pageSlug' => 'accounts'])

@section('content')
    <div class="row mt-5">
        <div class="col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold">Subscribed Accounts</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Contact Number</th>
                                    <th>Date Subscribed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">No Data Available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection