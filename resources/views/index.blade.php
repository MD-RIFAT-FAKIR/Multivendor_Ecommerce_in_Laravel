@extends('dashboard')
@section('user')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span>My Account
                </div>
            </div>
        </div>
        <div class="page-content pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <div class="row">

                            <!-- sidebar menu -->
                              @include('frontend.body.dashboard_sidebar_menu')
                            <!-- end sidebar menu -->

                            <div class="col-md-9">
                                <div class="tab-content account dashboard-content pl-50">
                                    <div class="tab-pane fade active show" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                                        
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0">Hello {{Auth::user()->username}} !</h3>
                                                <br/>
                                                <img id="showImage" src="{{(!empty($UserData->photo)) ? url('upload/user_images/'.$UserData->photo) : url('upload/no_image.jpg')}}" alt="User" class="rounded-circle p-1 bg-primary" style="width: 100px; height: 100px;">
                                            </div>
                                            <div class="card-body">
                                                <p>
                                                    From your account dashboard. you can easily check &amp; view your <a href="#">recent orders</a>,<br />
                                                    manage your <a href="#">shipping and billing addresses</a> and <a href="#">edit your password and account details.</a>
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection