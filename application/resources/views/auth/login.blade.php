@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="w-50 center border rounded px-3 py-3 mx-auto">
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" value="" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3 d-grid">
        <button name="submit" type="submit" class="btn btn-primary">Login</button>
      </div>
      @if ($errors->any())
      <div class="mb-3 d-grid errors">
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
          {{ $error }}
        </div>
        @endforeach
      </div>
      <script>
        setTimeout(function() {
          $('.errors').children().hide();
        }, 3000);
      </script>
      @endif
    </form>
  </div>
</div>
@endsection