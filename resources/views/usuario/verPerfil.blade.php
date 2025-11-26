@extends('layouts.layoutUsuario')
@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-6">

    {{-- Card de perfil completo --}}
    <div class="card p-8 shadow-md rounded-xl bg-white border border-gray-200">

        {{-- Título general --}}
        <h1 class="text-2xl font-semibold mb-6 border-b pb-3">Mi Perfil</h1>

        {{-- Datos personales --}}
        <section class="mb-8">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Datos personales</h2>

    <form method="POST" action="{{ route('usuario.updatePerfil') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Nombre --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" disabled
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Apellido --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido', $user->apellido) }} " disabled
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Email (NO editable) --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" value="{{ $user->mail }}" disabled
                       class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-600 cursor-not-allowed">
            </div>

            {{-- Fecha de nacimiento --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" disabled
                       value="{{ old('fecha_nacimiento', $user->fecha_nacimiento) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- DNI (NO editable) --}}
            <div>
                <label class="text-sm font-medium text-gray-700">DNI</label>
                <input type="text" value="{{ $user->dni }}" disabled
                       class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-600 cursor-not-allowed">
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            

            {{-- Ocupación --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-700">Ocupación</label>
                <input type="text" name="ocupacion" value="{{ old('ocupacion', $user->ocupacion) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Obra Social --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Obra Social</label>
                <select name="obra_social_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                    <option value="">Seleccionar...</option>
                    @foreach ($obrasSociales as $os)
                        <option value="{{ $os['id'] }}" {{ $user->obra_social_id == $os['id'] ? 'selected' : '' }}>
                            {{ $os['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Número de afiliado --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Número de afiliado</label>
                <input type="text" name="numero_afiliado"
                       value="{{ old('numero_afiliado', $user->numero_afiliado) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

        </div>

        {{-- Botones --}}
        <div class="flex justify-end mt-6 gap-4">
            <a href="{{ route('change.password', ['email' => $user->mail]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition">
                <i class="fas fa-key"></i> Cambiar contraseña
            </a>

            <button type="submit"
               class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-700 transition">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>

    </form>
</section>


        {{-- Resumen de tratamientos --}}
        @if($resumenTratamientos && $resumenTratamientos->isNotEmpty())
            <section class="mt-10">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Resumen de Tratamientos</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border">Objetivo</th>
                                <th class="p-2 border">Estado</th>
                                <th class="p-2 border">Etapa</th>
                                <th class="p-2 border">Médico</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumenTratamientos as $t)
                                <tr>
                                    <td class="p-2 border">{{ $t['objetivo'] }}</td>
                                    <td class="p-2 border">{{ $t['estado'] }}</td>
                                    <td class="p-2 border">{{ $t['etapa'] }}</td>
                                    <td class="p-2 border">{{ $t['medico'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

    </div>
</div>
@endsection
