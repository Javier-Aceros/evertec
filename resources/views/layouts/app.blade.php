@include('layouts.head')
  <body>
    <div class="page">
      {{-- Page Header --}}
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

          <a class="navbar-brand" href="#">Evertec</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExample07">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">Crear orden</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ url('/order/index/') }}">Listado de órdenes</a>
              </li>
            </ul>
          </div>

        </div>
      </nav>

      @section('content')
      @show
    </div>

    {{-- Global Mailform Output --}}
    <div class="snackbars" id="form-output-global"></div>

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    {{-- Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    {{-- Bootstrap JS --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    {{-- Javascript --}}
    <script src="{{ URL::to('/js/core.min.js') }}"></script>
    <script src="{{ URL::to('/js/script.js') }}"></script>
  </body>
</html>