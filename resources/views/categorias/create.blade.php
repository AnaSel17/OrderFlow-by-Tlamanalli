@extends('adminlte::page')

@section('title', 'Nueva categoría')

@section('content_header')
    <h1>Crear nueva categoría</h1>
@stop

@section('content')
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" 
            id="nombre"
       class="form-control-dark"
       value="{{ old('nombre', $categoria->nombre ?? '') }}"
       minlength="3"
       maxlength="100"
       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-.,()]+"
       title="Solo letras, números, espacios y signos básicos (- , . ( ))." required>
        </div>
        <div class="form-group mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" id="descripcion"
          class="form-control-dark"
          rows="3"
          minlength="5"
          maxlength="255"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
