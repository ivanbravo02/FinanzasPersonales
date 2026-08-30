<x-filament-panels::page>
    <div class="space-y-6">
        @if($corteActivo)
            {{-- Caja abierta --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-filament::card>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Fondo inicial</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($corteActivo->fondo_inicial, 2) }}</p>
                    </div>
                </x-filament::card>
                <x-filament::card>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Total ventas</p>
                        <p class="text-2xl font-bold text-green-600">
                            ${{ number_format($corteActivo->ventas()->where('estado','completada')->sum('total'), 2) }}
                        </p>
                    </div>
                </x-filament::card>
                <x-filament::card>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Ventas realizadas</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $corteActivo->ventas()->where('estado','completada')->count() }}
                        </p>
                    </div>
                </x-filament::card>
                <x-filament::card>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Abierta desde</p>
                        <p class="text-lg font-semibold text-gray-700">
                            {{ $corteActivo->abierto_en->format('H:i') }}
                        </p>
                        <p class="text-sm text-gray-400">{{ $corteActivo->abierto_en->format('d/m/Y') }}</p>
                    </div>
                </x-filament::card>
            </div>

            {{-- Desglose por método de pago --}}
            <x-filament::card>
                <h3 class="text-lg font-semibold mb-4">Desglose de ventas</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Efectivo</p>
                        <p class="text-xl font-bold text-green-600">
                            ${{ number_format($corteActivo->ventas()->where('estado','completada')->where('metodo_pago','efectivo')->sum('total'), 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tarjeta</p>
                        <p class="text-xl font-bold text-yellow-600">
                            ${{ number_format($corteActivo->ventas()->where('estado','completada')->where('metodo_pago','tarjeta')->sum('total'), 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Transferencia</p>
                        <p class="text-xl font-bold text-blue-600">
                            ${{ number_format($corteActivo->ventas()->where('estado','completada')->where('metodo_pago','transferencia')->sum('total'), 2) }}
                        </p>
                    </div>
                </div>
            </x-filament::card>

            {{-- Últimas ventas --}}
            <x-filament::card>
                <h3 class="text-lg font-semibold mb-4">Últimas ventas de este corte</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2">Folio</th>
                            <th class="pb-2">Hora</th>
                            <th class="pb-2">Cliente</th>
                            <th class="pb-2">Pago</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($corteActivo->ventas()->with('cliente')->where('estado','completada')->latest()->take(10)->get() as $venta)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 font-mono">{{ $venta->folio }}</td>
                            <td class="py-2">{{ $venta->created_at->format('H:i') }}</td>
                            <td class="py-2">{{ $venta->cliente?->nombre ?? 'Público general' }}</td>
                            <td class="py-2 capitalize">{{ $venta->metodo_pago }}</td>
                            <td class="py-2 text-right font-semibold">${{ number_format($venta->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-filament::card>
        @else
            {{-- Caja cerrada --}}
            <x-filament::card>
                <div class="text-center py-12">
                    <x-heroicon-o-lock-closed class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                    <h2 class="text-xl font-semibold text-gray-700">La caja está cerrada</h2>
                    <p class="text-gray-500 mt-2">Abre la caja para comenzar a registrar ventas.</p>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
