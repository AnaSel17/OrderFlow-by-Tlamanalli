@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <h1>Dashboard</h1>
@stop

@section('content')
  {{-- Métricas superiores --}}
  <div class="row">
    @foreach ([
      ['icon'=>'fas fa-dollar-sign','label'=>'Ventas del día','value'=>'$1,250'],
      ['icon'=>'fas fa-clipboard-check','label'=>'Pedidos activos','value'=>'15'],
      ['icon'=>'fas fa-shopping-cart','label'=>'Productos más vendidos','value'=>'Espresso'],
      ['icon'=>'fas fa-user','label'=>'Nuevos clientes','value'=>'8'],
    ] as $c)
      <div class="col-md-6 col-xl-3 mb-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <span class="btn btn-sm btn-light mr-3" style="border-radius:12px;">
              <i class="{{ $c['icon'] }}"></i>
            </span>
            <div>
              <div class="text-muted">{{ $c['label'] }}</div>
              <div class="h4 mb-0">{{ $c['value'] }}</div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Panel de gráficas con divisoria --}}
  <div class="card">
    <div class="card-body split">
      <div>
        <canvas id="salesLine" height="140"></canvas>
      </div>
      <div>
        <canvas id="ordersBar" height="140"></canvas>
      </div>
    </div>
  </div>
@stop

@push('js')
<script>
  // Paleta Tonalli (debes tener Chart.js activo en config/adminlte.php)
  const ton = { green:'#1F7A63', orange:'#E1782E', purple:'#6C3FA7', red:'#C94D3F' };

  if (window.Chart) {
    // Línea: Ventas por día
    new Chart(document.getElementById('salesLine'), {
      type: 'line',
      data: {
        labels: ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'],
        datasets: [{
          label: 'Ventas',
          data: [10,12,9,14,18,16,20],
          borderColor: ton.green,
          backgroundColor: 'transparent',
          fill: false,
          tension: .35
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: '#E6DFD2' } }
        }
      }
    });

    // Barras: Pedidos por categoría (ejemplo)
    new Chart(document.getElementById('ordersBar'), {
      type: 'bar',
      data: {
        labels: ['A','B','C','D'],
        datasets: [{
          label: 'Pedidos',
          data: [6,9,12,15],
          backgroundColor: [ton.green, ton.orange, ton.purple, ton.red],
          borderWidth: 0
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { color: '#E6DFD2' } }
        }
      }
    });
  }
</script>
@endpush