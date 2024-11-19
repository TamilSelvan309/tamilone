@extends('layout.app')
<!-- @section('title','Login Page') -->
@section('content')
     <div class="container">
     <div class="mt-5">
            @if($errors->any())
            <div class="col-12"> 
                @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
                @endforeach
            </div>
            @endif

            @if(session()->has('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
           @endif
            @if(session()->has('success'))
            <div class="alert alert-success">{{session('success')}}</div>
            @endif

        </div>
     <form action="{{route('employee.loginpost')}}" method="post" class="ms-auto me-auto mt-4" style="width: 400px;">
     @csrf
  <div class="mb-3">
    <label class="form-label">Email address</label>
    <input type="email" class="form-control" name="email" id="email" autocomplete="off">
    <!-- @if($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{$errors->first('email')}}</strong>
                    </span>
                @endif -->
  </div>
  <div class="mb-3">
    <label  class="form-label">Password</label>
    <input type="password" class="form-control" name="password" id="password">
    <!-- @if($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{$errors->first('password')}}</strong>
                    </span>
                @endif -->
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button><br><br>
  <p>if new user, Please Register</p>
  <a href="{{route('employee.create')}}" class="btn btn-primary btn-xs ">Register</a>
  
</form>
     </div>

@endsection