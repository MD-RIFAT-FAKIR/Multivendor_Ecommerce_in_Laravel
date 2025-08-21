@extends('dashboard')
@section('user')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span>Change Password
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
                    <h5>Change Password</h5>
                  </div>
                  <div class="card-body">
                    <form
                      action="{{route('user.update.password')}}"
                      method="post"
                    >
                      @csrf
                      <div class="row">
                        @if(session('status'))
                        <div class="alert alert-success" role="alert">
                          {{(session('status'))}}
                        </div>
                        @elseif(session('error'))
                        <div class="alert alert-danger" role="alert">
                          {{(session('error'))}}
                        </div>
                        @endif

                        <div class="row mb-4">
                          <div class="col-sm-2">
                            <h6 class="mb-0">Old Password</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <input
                              type="password"
                              id="current_password"
                              class="form-control @error('old_password') is-invalid @enderror"
                              name="old_password"
                              placeholder="Enter Old Password"
                            />
                            @error('old_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-sm-2">
                            <h6 class="mb-0">New Password</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <input
                              type="password"
                              id="new_password"
                              class="form-control @error('new_password') is-invalid @enderror"
                              name="new_password"
                              placeholder="Enter New Password"
                            />
                            @error('new_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-sm-2">
                            <h6 class="mb-0">Confirm New Password</h6>
                          </div>
                          <div class="col-sm-9 text-secondary">
                            <input
                              type="password"
                              id="new_password_confirmation"
                              class="form-control"
                              name="new_password_confirmation"
                              placeholder="Confirm New Password"
                            />
                          </div>
                        </div>
                        <div class="col-md-12">
                          <button
                            type="submit"
                            class="btn btn-fill-out submit font-weight-bold"
                            name="submit"
                            value="Submit"
                          >
                            Save Change
                          </button>
                        </div>
                      </div>
                    </form>
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