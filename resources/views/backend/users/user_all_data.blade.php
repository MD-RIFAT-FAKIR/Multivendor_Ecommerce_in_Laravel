@extends('admin.admin_dashboard')

@section('admin')
<div class="page-content">
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
<div class="breadcrumb-title pe-3">All Users Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">All Users Data</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<hr/>
<div class="card">
<div class="card-body">
    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                  <th>Sl</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ $item->name }}</td>
                    <td><img src="{{ !empty($item->photo) ? url('upload/user_images/'.$item->photo) : url('upload/no_image.jpg')}}" class="rounded-circle p-1 bg-primary" style="width: 50px; heighr: 50px;"></td>
                    <td>{{ $item->email  }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>
                      <span class="badge rounded-pill bg-success">{{ $item->status }}</span>                      
                    </td>                
                    <td>
                        <a href="{{ route('edit.subcategory',$item->id) }}" class="btn btn-info">Edit</a>
                        <a href="{{ route('delete.subcategory',$item->id) }}" class="btn btn-danger" id="delete">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                  <th>Sl</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
</div>
@endsection