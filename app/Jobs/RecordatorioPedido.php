<?php

namespace App\Jobs;

use App\Models\Pedido;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Log;

class RecordatorioPedido implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $pedido;

    /**
     * Create a new job instance.
     */
    public function __construct(Pedido $pedido)
    {
         $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
         $pedido = Pedido::find($this->pedido->id);
        if ($pedido && $pedido->estado === 'pendiente') {
            // Aquí puedes mandar notificación, log, o evento
            Log::info("🔔 Recordatorio: Pedido #{$pedido->id} sigue pendiente después de 10 minutos.");
        }
    }
}
