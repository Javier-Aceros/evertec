@section('head')
@show
@extends('layouts.app')
@section('title', 'Creación de orden')

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
          <button type="button" class="btn btn-primary" data-dismiss="modal">
            Entendido
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Cuerpo de la página --}}
    <div class="container">
      <div class="py-5 text-center">

        <h3>Generar nueva orden</h3>

        {{-- Formulario --}}
        <form method="POST" id="order_form" novalidate>
          <div class="row">
            {!! csrf_field() !!}

            <div class="col-md-12 form-group">
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control" name="nombre" placeholder="Ingrese su nombre">
            </div>

            <div class="col-md-12 form-group">
              <label for="email">Correo electrónico</label>
              <input type="email" class="form-control" name="email" placeholder="Ingrese su dirección de correo">
            </div>

            <div class="col-md-12 form-group">
              <label for="celular">Celular</label>
              <input type="number" class="form-control" name="celular" placeholder="Ingrese su celular">
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg btn-block" form="order_form" id="order_submit">
            Comprar
          </button>
        </form>

      </div>
    </div>
@stop

{{-- jQuery 2.2.3 --}}
<script src="{{ URL::to('/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<script src="{{ URL::to('/js/order_create.js') }}"></script>