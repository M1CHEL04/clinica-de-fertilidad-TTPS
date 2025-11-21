@extends('layouts.layoutInterno')

@section('title', 'Cargar Estudios')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Cargar Estudios</h1>
        <p class="page-subtitle">Completá o revisá los estudios del tratamiento.</p>
    </div>
    <div>
        <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Tratamiento
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="space-y-10">

    {{-- 🟦 ESTUDIOS PENDIENTES --}}
    <div class="card p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900">
            🧪 Estudios pendientes de carga
        </h2>

        @if ($estudiosPendientes->isEmpty())
            <p class="text-gray-600 text-center py-6">
                🎉 No hay estudios pendientes.
            </p>
        @else

            <form action="{{ route('tratamiento.guardar-estudios', $tratamiento->id) }}" method="POST">
                @csrf

                <div class="space-y-8">

                    @foreach ($estudiosPendientes as $tipo => $grupo)

                        {{-- CABECERA DEL TIPO --}}
                        <h3 class="text-lg font-bold text-blue-700 border-b pb-1">
                            {{ $tipo }}
                        </h3>

                        <div class="space-y-4">
                            @foreach ($grupo as $estudio)
                                <div class="border rounded-xl p-4 bg-gray-50">
                                    <p class="font-medium text-gray-700 mb-2">
                                        {{ $estudio->nombre }}
                                    </p>

                                    <textarea 
                                        name="resultados[{{ $estudio->id }}]"
                                        class="input w-full"
                                        rows="3"
                                        placeholder="Ingresá el resultado del estudio..."
                                    ></textarea>
                                </div>
                            @endforeach
                        </div>

                    @endforeach

                </div>

                <div class="mt-6 flex justify-end">
                    <button class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Guardar Resultados
                    </button>
                </div>

            </form>

        @endif
    </div>

    
    {{-- 🟩 ESTUDIOS YA CARGADOS --}}
    <div class="card p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900">
            📄 Estudios ya cargados
        </h2>

        @if ($estudiosCompletados->isEmpty())
            <p class="text-gray-600 text-center py-6">
                No hay estudios cargados todavía.
            </p>
        @else

            <div class="space-y-8">

                @foreach ($estudiosCompletados as $tipo => $grupo)

                    <h3 class="text-lg font-bold text-green-700 border-b pb-1">
                        {{ $tipo }}
                    </h3>

                    @foreach ($grupo as $estudio)
                        <div class="border rounded-xl p-4 bg-white shadow-sm">
                            <p class="font-medium text-gray-900">
                                {{ $estudio->nombre }}
                            </p>

                            <p class="mt-2 text-gray-700 whitespace-pre-line">
                                {{ $estudio->resultado }}
                            </p>
                        </div>
                    @endforeach

                @endforeach

            </div>

        @endif
    </div>

</div>

@endsection
