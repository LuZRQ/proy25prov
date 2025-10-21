<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;
use App\Traits\Auditable; 
class ResetStockDiario extends Command
{ use Auditable;

    protected $signature = 'stock:reset-diario';
    protected $description = 'Reinicia el stock y los vendidos diarios de los productos preparados cada día.';

    public function handle()
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $producto->stock = $producto->stock_inicial;

            if (isset($producto->vendidos_dia)) {
                $producto->vendidos_dia = 0;
            }

            $producto->save();
            $this->logAction(
                "Stock diario del producto '{$producto->nombre}' reiniciado a su valor inicial.",
                'Stock',
                'Automático'
            );
        }

        Log::info('Stock y vendidos diarios reiniciados correctamente el ' . now());
        $this->info('Stock diario reiniciado correctamente.');
    }
}
