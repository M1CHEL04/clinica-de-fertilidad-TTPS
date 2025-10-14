@extends('layouts.layoutUsuario')
@section('title', 'Servicios - Fertilia')

@section('content')
<!-- Header Section -->
<div class="hero-section text-center mb-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            <i class="fas fa-hospital text-blue-600 mr-3"></i>
            Nuestros Servicios
        </h1>
        <p class="text-xl text-gray-600 mb-6 max-w-3xl mx-auto leading-relaxed">
            Ofrecemos servicios especializados de reproducción asistida con tecnología de vanguardia 
            y los más altos estándares de calidad para apoyarte en tu camino hacia la paternidad.
        </p>
    </div>
</div>

<!-- Services Grid -->
<div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto mb-8">
    
    <!-- Donación de Semen -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-blue-100">
            <i class="fas fa-male text-blue-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Donación de Semen</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Programa de donación de espermatozoides con donantes cuidadosamente seleccionados 
            que cumplen estrictos criterios médicos, psicológicos y físicos.
        </p>
        <div class="bg-blue-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-blue-800 mb-2 text-sm">Características del servicio:</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Donantes anónimos evaluados integralmente</li>
                <li>• Análisis genéticos y médicos completos</li>
                <li>• Compatibilidad física y sanguínea</li>
                <li>• Muestras criopreservadas y cuarentenadas</li>
            </ul>
        </div>
    </div>

    <!-- Donación de Óvulos -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-pink-100">
            <i class="fas fa-female text-pink-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Donación de Óvulos</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Programa de ovodonación con donantes jóvenes y sanas que permiten a mujeres 
            con problemas de fertilidad ovárica acceder a óvulos de alta calidad.
        </p>
        <div class="bg-pink-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-pink-800 mb-2 text-sm">Características del servicio:</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Donantes entre 18-30 años evaluadas</li>
                <li>• Estudios psicológicos y médicos exhaustivos</li>
                <li>• Sincronización de ciclos menstruales</li>
                <li>• Seguimiento médico especializado</li>
            </ul>
        </div>
    </div>

    <!-- Criopreservación de Embriones -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-green-100">
            <i class="fas fa-snowflake text-green-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Criopreservación de Embriones</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Conservación de embriones mediante técnicas de vitrificación ultrarrápida 
            que mantienen su viabilidad para futuros tratamientos de fertilidad.
        </p>
        <div class="bg-green-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-green-800 mb-2 text-sm">Características del servicio:</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Técnica de vitrificación ultrarrápida</li>
                <li>• Almacenamiento en nitrógeno líquido (-196°C)</li>
                <li>• Tasas de supervivencia superiores al 95%</li>
                <li>• Conservación a largo plazo garantizada</li>
            </ul>
        </div>
    </div>

    <!-- Criopreservación de Óvulos -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-purple-100">
            <i class="fas fa-thermometer text-purple-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Criopreservación de Óvulos</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Preservación de la fertilidad femenina mediante congelación de óvulos 
            para mujeres que desean posponer la maternidad o por indicaciones médicas.
        </p>
        <div class="bg-purple-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-purple-800 mb-2 text-sm">Características del servicio:</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Estimulación ovárica personalizada</li>
                <li>• Punción folicular ambulatoria</li>
                <li>• Vitrificación de óvulos maduros</li>
                <li>• Preservación de fertilidad futura</li>
            </ul>
        </div>
    </div>

</div>
@endsection