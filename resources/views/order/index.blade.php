@section('head')
@show
@extends('layouts.app')
@section('title', 'Listado de órdenes')

@section('content')
  {{-- Cuerpo de la página --}}
  <div class="container">
    <div class="py-5 text-center">
      <h1>Ordenes creadas</h1>
      <table class="table">

        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo electrónico</th>
            <th scope="col">Celular</th>
            <th scope="col">Estado de la orden</th>
            <th scope="col">Antigüedad</th>
          </tr>
        </thead>

        <tbody>
          @if(isset($orders) && count($orders) > 0)
            @foreach ($orders as $order)
              <tr>
                <th scope="row"><a href="{{ url('/order/status/'.$order->id) }}">{{ $order->id }}</a></th>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_email }}</td>
                <td>{{ $order->customer_mobile }}</td>
                <td>{{ $order->status_es }}</td>
                <td>{{ $order->diff }}</td>
              </tr>
            @endforeach

            {{-- Paginación --}}
            <div id="links">
              <nav aria-label="Page navigation">
                {{ $orders->links() }}
              </nav>
            </div>

          @else
            {{-- Si no hay órdenes creadas se muestra un mensaje --}}
            <tr>
              <td colspan="6">No hay ninguna orden creada</td>
            </tr>
          @endif
        </tbody>

      </table>
    </div>
  </div>
@stop

{{-- jQuery 2.2.3 --}}
<script src="{{ URL::to('/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ URL::to('/js/order_index.js') }}"></script>