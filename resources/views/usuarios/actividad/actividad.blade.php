@extends('adminlte::page')

@section('title', 'Historial de Actividad')

@push('css')
    {{-- La directiva correcta de AdminLTE para inyectar CSS --}}
    <link rel="stylesheet" href="{{ asset('css/actividad.css') }}">
    
    {{-- Opcional: Para íconos de Font Awesome v6 que AdminLTE podría no tener por defecto --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
<div class="container-actividad py-4">

    <!-- Header y Acciones de Exportación -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h1 class="m-0 text-dark">Historial de Movimientos</h1>
      
    </div>
    <p class="subtitle">Registro de ingresos y actividades del sistema</p>

    <!-- Barra de Filtros Avanzados -->
    <div class="row filter-bar-custom mb-4">
        <div class="col-12 col-md-9 d-flex flex-wrap align-items-center mb-3 mb-md-0 filter-group-custom">
            
            {{-- Dropdown para Movimientos (Necesita JS/Livewire) --}}
            <div class="dropdown mr-2">
                <button class="btn btn-light dropdown-toggle btn-filter-custom" type="button" data-toggle="dropdown">
                    Todos los movimientos <i class="fas fa-caret-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Ingresos</a>
                    <a class="dropdown-item" href="#">Registros</a>
                    <a class="dropdown-item" href="#">Modificaciones</a>
                    <a class="dropdown-item" href="#">Eliminaciones</a>
                </div>
            </div>

            {{-- Dropdown para Usuarios (Necesita JS/Livewire) --}}
            <div class="dropdown mr-2">
                <button class="btn btn-light dropdown-toggle btn-filter-custom" type="button" data-toggle="dropdown">
                    Todos los usuarios <i class="fas fa-caret-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">María González</a>
                    <a class="dropdown-item" href="#">Carlos Ruiz</a>
                    <a class="dropdown-item" href="#">Ana Martínez</a>
                </div>
            </div>

            <input type="date" class="form-control date-filter-custom mr-2" style="max-width: 150px;">
            <input type="date" class="form-control date-filter-custom mr-2" style="max-width: 150px;">
            <button class="btn btn-info filter-btn-custom">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
        </div>

     
    </div>

        <!-- Bloques de Métricas / Cards de Valor -->
    <div class="row metrics-grid mb-4">
        {{-- Tarjeta 1: Total Movimientos (Primary/Indigo) --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box bg-indigo elevation-2 metric-card primary">
                <span class="info-box-icon metric-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content metric-info">
                    <span class="info-box-text metric-title">Total Movimientos</span>
                    <span class="info-box-number metric-value">245</span>
                    <span class="metric-change up"><i class="fas fa-arrow-up"></i> 12% esta semana</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Ingresos Hoy (Success/Emerald) --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box bg-success elevation-2 metric-card success">
                <span class="info-box-icon metric-icon"><i class="fas fa-arrow-right"></i></span>
                <div class="info-box-content metric-info">
                    <span class="info-box-text metric-title">Ingresos Hoy</span>
                    <span class="info-box-number metric-value">18</span>
                    <span class="metric-change down"><i class="fas fa-clock"></i> Últimas 24 horas</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Registros Nuevos (Info/Cyan) --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box bg-info elevation-2 metric-card info">
                <span class="info-box-icon metric-icon"><i class="fas fa-box"></i></span>
                <div class="info-box-content metric-info">
                    <span class="info-box-text metric-title">Registros Nuevos</span>
                    <span class="info-box-number metric-value">7</span>
                    <span class="metric-change"><i class="fas fa-calendar-alt"></i> Esta semana</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 4: Usuarios Activos (Warning/Yellow) --}}
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="info-box bg-warning elevation-2 metric-card warning">
                <span class="info-box-icon metric-icon"><i class="fas fa-user-friends"></i></span>
                <div class="info-box-content metric-info">
                    <span class="info-box-text metric-title">Usuarios Activos</span>
                    <span class="info-box-number metric-value">12</span>
                    <span class="metric-change"><i class="fas fa-circle online"></i> En línea ahora</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenedor Principal: Tabla -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary movements-log">
                <div class="card-header border-0">
                    <h3 class="card-title">Registro de Movimientos</h3>
                    {{-- CAMPO DE BÚSQUEDA FLOTANTE DERECHA --}}
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right search-input-custom" placeholder="Buscar en historial...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default search-icon-custom"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-valign-middle data-table">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Acción</th>
                                <th>Detalles</th>
                                <th>IP</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- FILA 1: Ingreso Exitoso --}}
                            <tr>
                                <td>
                                    <span class="date-text">19/10/2025</span><br>
                                    <small class="time-text">14:32:15</small>
                                </td>
                                <td>
                                    <span class="tag tag-ingreso">
                                        <i class="fas fa-arrow-right mr-1"></i> Ingreso
                                    </span>
                                </td>
                                <td>
                                    <span class="user-name">María González</span><br>
                                    <small class="user-id">ID: 1</small>
                                </td>
                                <td>Gerente</td>
                                <td>Inicio de sesión</td>
                                <td>Acceso al panel de administración</td>
                                <td>192.168.1.185</td>
                                <td><span class="status-tag success">Exitoso</span></td>
                            </tr>
                            {{-- FILA 2: Registro Exitoso --}}
                            <tr>
                                <td>
                                    <span class="date-text">19/10/2025</span><br>
                                    <small class="time-text">14:28:42</small>
                                </td>
                                <td>
                                    <span class="tag tag-registro">
                                        <i class="fas fa-clipboard-list mr-1"></i> Registro
                                    </span>
                                </td>
                                <td>
                                    <span class="user-name">Carlos Ruiz</span><br>
                                    <small class="user-id">ID: 2</small>
                                </td>
                                <td>Barista</td>
                                <td>Nuevo usuario creado</td>
                                <td>Registro de nuevo empleado</td>
                                <td>192.168.1.108</td>
                                <td><span class="status-tag success">Exitoso</span></td>
                            </tr>
                            {{-- FILA 3: Modificación Exitosa --}}
                            <tr>
                                <td>
                                    <span class="date-text">19/10/2025</span><br>
                                    <small class="time-text">14:15:30</small>
                                </td>
                                <td>
                                    <span class="tag tag-modificacion">
                                        <i class="fas fa-tools mr-1"></i> Modificación
                                    </span>
                                </td>
                                <td>
                                    <span class="user-name">Ana Martínez</span><br>
                                    <small class="user-id">ID: 3</small>
                                </td>
                                <td>Supervisor</td>
                                <td>Actualización de permisos</td>
                                <td>Modificó permisos del rol Mesero</td>
                                <td>192.168.1.112</td>
                                <td><span class="status-tag success">Exitoso</span></td>
                            </tr>
                            {{-- FILA 4: Ingreso Fallido --}}
                            <tr>
                                <td>
                                    <span class="date-text">19/10/2025</span><br>
                                    <small class="time-text">13:58:05</small>
                                </td>
                                <td>
                                    <span class="tag tag-ingreso">
                                        <i class="fas fa-arrow-right mr-1"></i> Ingreso
                                    </span>
                                </td>
                                <td>
                                    <span class="user-name">Laura Sánchez</span><br>
                                    <small class="user-id">ID: 5</small>
                                </td>
                                <td>Cajero</td>
                                <td>Intento de sesión</td>
                                <td>Credenciales incorrectas</td>
                                <td>192.168.1.125</td>
                                <td><span class="status-tag error">Fallido</span></td>
                            </tr>
                            {{-- FILA 5: Ejemplo de Eliminación --}}
                            <tr>
                                <td>
                                    <span class="date-text">19/10/2025</span><br>
                                    <small class="time-text">12:10:00</small>
                                </td>
                                <td>
                                    <span class="tag tag-eliminacion">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminación
                                    </span>
                                </td>
                                <td>
                                    <span class="user-name">Admin</span><br>
                                    <small class="user-id">ID: 0</small>
                                </td>
                                <td>Administrador</td>
                                <td>Eliminación de producto</td>
                                <td>Producto X eliminado</td>
                                <td>192.168.1.100</td>
                                <td><span class="status-tag success">Exitoso</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-muted">Mostrando 5 de 245 registros</p>
                    <nav aria-label="Paginación">
                        <ul class="pagination pagination-sm m-0 pagination-controls">
                            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    
    <!-- CONTENEDOR PRINCIPAL VERTICAL: TIMELINE -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card activity-timeline">
                <div class="card-header border-0">
                    <h3 class="card-title">Actividad Reciente (Timeline)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="timeline-list">
                        
                        <div class="timeline-item">
                            <div class="timeline-dot success"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Inicio de sesión</p>
                                <p class="timeline-details">Acceso al panel de administración</p>
                                <p class="timeline-meta text-muted">A las 14:32:15</p>
                                <p class="timeline-user">María González <span class="role badge badge-secondary">Gerente</span> - 192.168.1.185</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot info"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Nuevo usuario creado</p>
                                <p class="timeline-details">Registro de nuevo empleado</p>
                                <p class="timeline-meta text-muted">A las 14:28:42</p>
                                <p class="timeline-user">Carlos Ruiz <span class="role badge badge-secondary">Barista</span> - 192.168.1.108</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot warning"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Actualización de permisos</p>
                                <p class="timeline-details">Modificó permisos del rol Mesero</p>
                                <p class="timeline-meta text-muted">A las 14:15:30</p>
                                <p class="timeline-user">Ana Martínez <span class="role badge badge-secondary">Supervisor</span> - 192.168.1.112</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot error"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Intento de sesión fallido</p>
                                <p class="timeline-details">Credenciales incorrectas</p>
                                <p class="timeline-meta text-muted">A las 13:58:05</p>
                                <p class="timeline-user">Laura Sánchez <span class="role badge badge-secondary">Cajero</span> - 192.168.1.125</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
