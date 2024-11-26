@extends('layouts.app')

@section('content')
<!-- <h3><img src="https://www.openbrewerydb.org/_app/immutable/assets/obdb-logo-sm.63b3b090.png" alt="" style="width: 100px;" class="mx-auto"></h3> -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#">
    <img src="https://www.openbrewerydb.org/_app/immutable/assets/obdb-logo-sm.63b3b090.png" height="30"
      class="d-inline-block align-top" alt="">
    List Breweries
  </a>
  <a href="{{ route('home') }}" class="btn btn-primary">Home</a>
</nav>

<style>
  a.disabled {
    pointer-events: none;
    cursor: default;
  }
</style>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Brewery Type</th>
      <th scope="col">Country</th>
      <th scope="col">City</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
    <tr>
      <th scope="row">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</th>
      <td>{{ $item->name }}</td>
      <td>{{ $item->brewery_type }}</td>
      <td>{{ $item->country }}</td>
      <td>{{ $item->city }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="mb-3">
  <p>Page #{{ $data->currentPage() }} (per page: {{ $data->perPage() }})</p>
</div>
<form class="row g-3">
  <div class="form-group mb-3">
    <label for="per_page">Customize per page param</label>
    <select id="per_page" name="per_page" class="form-select form-select-sm w-25" aria-label="Per Page param">
      @foreach (range(1, 50) as $item) {
      <option value="{{ $item }}" @php if($data->perPage() == $item) echo "selected" @endphp>{{ $item }}</option>;
      }
      @endforeach
    </select>
  </div>
</form>
<div class="mb-3">
  <a href="{{ route('web_breweries') }}?page={{ $data->currentPage() - 1 }}" class="prev btn btn-primary @php if($data -> currentPage() == 1) echo ' disabled' @endphp">Prev Page</a>
  <a href="{{ route('web_breweries') }}?page={{ $data->currentPage() + 1 }}" class="next btn btn-primary">Next Page</a>
</div>
@endsection

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    // Your code to run since DOM is loaded and ready
    $('#per_page').on('change', function() {
      setPerPage();
      document.location = "{{ route('web_breweries') }}?page={{ $data->currentPage() }}&per_page=" + $('#per_page').val();
    });
    // call at each render
    setPerPage();
  });

  function setPerPage() {
    let value = $('#per_page').val();
    $('.prev').attr('href', "{{ route('web_breweries') }}?page={{ $data->currentPage() - 1 }}" + "&per_page=" + value)
    $('.next').attr('href', "{{ route('web_breweries') }}?page={{ $data->currentPage() + 1 }}" + "&per_page=" + value)
  }
</script>