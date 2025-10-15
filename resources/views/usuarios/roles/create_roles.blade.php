@extends('adminlte::page')

@section('title', 'Crear Nuevo Rol')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create-roles.css') }}">

<div class="rol-container">
    <h1>Crear Nuevo Rol</h1>
    <p class="subtitle">Define un nuevo rol dentro del sistema</p>

    <form>
        <div class="form-group">
            <label for="nombre">Nombre del Rol *</label>
            <input type="text" id="nombre" placeholder="Ej: Barista Senior">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" rows="3" placeholder="Describe las responsabilidades de este rol..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria">Categoría *</label>
                <select id="categoria">
                    <option value="">Selecciona una categoría</option>
                    <option>General</option>
                    <option>Operativo</option>
                    <option>Administrativo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="icono">Ícono</label>
                <select id="icono">
                    <option value="">Selecciona un ícono</option>
                    <option>☕ Barista</option>
                    <option>🧁 Pastelero</option>
                    <option>🛡️ Gerente</option>
                </select>
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <select id="color">
                    <option>Gris</option>
                    <option>Dorado</option>
                    <option>Café</option>
                    <option>Beige</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Permisos *</label>
            <div class="permisos">
                @foreach ([
                    'Gestión completa', 'Reportes', 'Administración de personal', 'Finanzas',
                    'Inventario', 'Atención al cliente', 'Tomar pedidos', 'Procesar pagos',
                    'Gestión de mesas', 'Supervisión', 'Gestión de turnos', 'Configuración del sistema'
                ] as $permiso)
                    <span class="chip">{{ $permiso }}</span>
                @endforeach
            </div>

            <div class="input-group">
                <input type="text" placeholder="Agregar permiso personalizado...">
                <button type="button">Agregar</button>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ url('/roles') }}" class="btn-cancelar">Cancelar</a>
            <button type="button" class="btn-crear">Crear Rol</button>
        </div>
    </form>
</div>
@endsection
