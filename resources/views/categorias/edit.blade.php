@extends('adminlte::page')

@section('title', 'Editar categoría')

@section('content_header')
    <h1>Editar categoría</h1>
@stop

@section('content')
    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $categoria->nombre }}" required>
        </div>
        <div class="form-group mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $categoria->descripcion }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop
