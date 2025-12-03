@extends('layouts.layoutInterno')

@section('content')
@if(session('rol') == 3)
<a href="{{ route('operador.tratamiento.detalle', $tratamiento->id) }}"
   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold mb-4">
    <i class="fas fa-arrow-left"></i>
    Volver
</a>
@elseif(session('rol') == 5)
<a href="{{ route('jefe.tratamiento.detalle', $tratamiento->id) }}"
   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold mb-4">
    <i class="fas fa-arrow-left"></i>
    Volver
</a>
@else
<a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}"
   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold mb-4">
    <i class="fas fa-arrow-left"></i>
    Volver
</a>
@endif
<div class="max-w-4xl mx-auto mt-10 space-y-10">

    {{-- ========================= --}}
    {{--       PROTOCOLO           --}}
    {{-- ========================= --}}
    <div class="bg-white shadow-md rounded-xl p-6 border">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-yellow-500"></i>
            Protocolo de Estimulación
        </h2>

        {{-- Si ya existe un protocolo, mostrarlo --}}
        @if($protocolo->count() > 0)
            <div class="space-y-3 mb-4">
                @foreach($protocolo as $med)
                    <div class="bg-green-100 p-4 rounded-md border border-green-300">
                        <p><strong>Tipo:</strong> {{ $med->tipoMedicacion->nombre }}</p>
                        <p><strong>Dosis:</strong> {{ $med->dosis }}</p>
                        <p><strong>Tiempo:</strong> {{ $med->tiempo }}</p>
                        <p><strong>Droga:</strong> {{ $med->droga }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Formulario para cargar protocolo --}}
       <form method="POST"
      action="{{ session('rol') == 5
            ? route('jefe.tratamiento.guardar-protocolo', $tratamiento->id)
            : route('tratamiento.guardar-protocolo', $tratamiento->id) }}"
      class="space-y-4">

            @csrf

            <div>
                <label class="font-semibold">Tipo de medicación</label>
                <select name="tipo_medicacion_id" class="w-full mt-1 border rounded-md p-2">
                    <option value="">Seleccione…</option>
                    @foreach($tiposMedicacion as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="font-semibold">Dosis</label>
                <input type="text" name="dosis" class="w-full mt-1 border rounded-md p-2">
            </div>

            <div>
                <label class="font-semibold">Tiempo (días)</label>
                <input type="number" name="tiempo" class="w-full mt-1 border rounded-md p-2">
            </div>

            <div>
                <label class="font-semibold">Droga</label>
                <input type="text" name="droga" class="w-full mt-1 border rounded-md p-2">
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                Agregar medicación
            </button>
        </form>
    </div>



    {{-- ========================= --}}
    {{--  CONSENTIMIENTO INFORMADO --}}
    {{-- ========================= --}}
    <div class="bg-white shadow-md rounded-xl p-6 border">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-file-pdf text-red-500"></i>
            Consentimiento Informado
        </h2>

        @if($tratamiento->consentimiento_pdf)
            <div class="bg-green-100 p-4 rounded-md mb-4">
                <p class="font-semibold">PDF cargado correctamente.</p>
                <a href="{{ route('tratamiento.descargar-consentimiento', $tratamiento->id) }}"
                class="text-blue-600 underline hover:text-blue-800">
                    Descargar consentimiento
                </a>
            </div>
        @else
            <p class="mb-4 text-gray-600">Aún no se ha cargado el consentimiento.</p>
        @endif

        @if($tratamiento->consentimiento_pdf == null)
        <form action="{{ route('tratamiento.subir-consentimiento', $tratamiento->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <div>
                <label class="font-semibold">Subir PDF firmado</label>
                <input type="file" name="consentimiento"
                       accept="application/pdf"
                       class="w-full mt-1 border rounded-md p-2">
            </div>

            <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg w-full">
                Subir consentimiento
            </button>
        </form>
        @endif
    </div>



    {{-- ========================= --}}
    {{--    ORDEN MÉDICA (LOCK)    --}}
    {{-- ========================= --}}
    <div class="bg-white shadow-md rounded-xl p-6 border">
        <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-prescription-bottle-alt text-blue-500"></i>
            Orden Médica
        </h2>

        @if(!$tratamiento->consentimiento_pdf)
            <button class="bg-gray-400 text-white px-4 py-2 rounded-lg w-full opacity-60 cursor-not-allowed">
                Subí el consentimiento informado para habilitar la orden médica
            </button>
        @else
            <form action="{{ route('tratamiento.enviar-orden-medica', $tratamiento->id) }}" method="POST">
                @csrf
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg w-full">
                    Enviar orden médica por email
                </button>
            </form>
        @endif
    </div>

</div>

@endsection
