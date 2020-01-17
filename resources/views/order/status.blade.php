@section('head')
@show
@extends('layouts.app')
@section('title', 'Estado de la orden')

@section('content')
  {{-- modal --}}
  <div class="modal" tabindex="-1" role="dialog" id="Modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- Mensaje --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Cuerpo de la página --}}
  <div class="container">
    <div class="py-5 text-center">

      {{-- Formulario --}}
      <form method="POST" id="buy_form" novalidate>
        {!! csrf_field() !!}
        <input type="hidden" name="customer_mobile" value="{{ $order->customer_mobile }}">
        <input type="hidden" name="customer_email" value="{{ $order->customer_email }}">
        <input type="hidden" name="customer_name" value="{{ $order->customer_name }}">
        <input type="hidden" name="id" value="{{ $order->id }}">

        @if(isset($_SERVER['REMOTE_ADDR']))
          <input type="hidden" name="ip" value="{{ $_SERVER['REMOTE_ADDR'] }} ">
        @else
          <input type="hidden" name="ip" value="{{ "127.0.0.1" }} ">
        @endisset
        @if(isset($_SERVER['HTTP_USER_AGENT']))
          <input type="hidden" name="browser" value="{{ $_SERVER['HTTP_USER_AGENT'] }} ">
        @else
          <input type="hidden" name="browser" value="{{ "Mozilla" }} ">
        @endisset

        <div class="container">
          <div class="row">

            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">Resumen de la orden</h3>

                  <div class="row">
                    <div class="col-md-12">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" value="{{ $order->customer_name }}" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="email">Correo electrónico</label>
                      <input type="text" class="form-control" value="{{ $order->customer_email }}" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="celular">Celular</label>
                      <input type="text" class="form-control" value="{{ $order->customer_mobile }}" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="celular">Valor de la compra</label>
                      <input type="text" class="form-control" value="{{ $order->product_price }}" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="celular">Creación</label>
                      <input type="text" class="form-control" value="{{ $order->diff }}" disabled>
                    </div>

                    <div class="col-md-12">
                      <label for="celular">Estado de pago</label>

                      @if ($order->status == 'REJECTED')
                        @php ($status = 'La transacción fue rechazada')
                        @php ($color = 'danger')
                      @elseif ($order->status == 'PAYED')
                        @php ($status = 'Su orden se encuentra pagada')
                        @php ($color = 'success')
                      @else
                        @php ($status = 'La transacción se encuentra pendiente de pago')
                        @php ($color = 'primary')
                      @endif

                      <div class="alert alert-{{ $color }}" role="alert">
                        {{ $status }}
                      </div>
                    </div>
                  </div>

                  @if ($order->status != 'PAYED')
                    <button type="submit" class="btn btn-primary btn-lg btn-block" form="buy_form" id="buy_submit">
                      Pagar
                    </button>
                  @endif
                    
                </div>
              </div>
            </div>
          </div><!--row-->
        </div><!--container-->
      </form>

    </div>
  </div>
@stop

{{-- jQuery 2.2.3 --}}
<script src="{{ URL::to('/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ URL::to('/js/order_status.js') }}"></script>