@extends('layouts.app')

@section('template_title')
{{ Auth::user()->name }}'s' Homepage
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="card">
        <div class="card-header">Home</div>
        <div class="card-body">
          <h5 class="card-title">Hello {{ Auth::user()->name }}, you are logged in!</h5>
          <p class="card-text">You can view brewery data <a href="{{ route('web_breweries') }}" class="btn btn-primary">View</a> </p>


          <p class="card-text">See you soon! <a href="{{ route('logout') }}" class="btn btn-primary">Logout</a></p>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection