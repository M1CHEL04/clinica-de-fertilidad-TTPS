@extends('layouts.layoutUsuario')
@section('title', 'Tratamientos - Fertilia')

@section('content')
<!-- Header Section -->
<div class="hero-section text-center mb-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            <i class="fas fa-microscope text-blue-600 mr-3"></i>
            Nuestros Tratamientos
        </h1>
        <p class="text-xl text-gray-600 mb-6 max-w-3xl mx-auto leading-relaxed">
            Ofrecemos tratamientos de reproducción asistida personalizados y de alta tecnología 
            para ayudarte a cumplir tu sueño de ser padre o madre.
        </p>
    </div>
</div>

<!-- Treatments Grid -->
<div class="grid lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
    
    <!-- Embarazo con Gametos Propios -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-blue-100">
            <i class="fas fa-baby text-blue-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Embarazo con Gametos Propios</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Técnicas de reproducción asistida utilizando los óvulos y espermatozoides de la propia pareja. 
            Ideal para parejas que tienen dificultades para concebir naturalmente.
        </p>
        <div class="bg-blue-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-blue-800 mb-2 text-sm">¿En qué consiste?</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Estimulación ovárica controlada</li>
                <li>• Extracción de óvulos mediante punción</li>
                <li>• Fertilización en laboratorio (FIV o ICSI)</li>
                <li>• Transferencia embrionaria al útero</li>
            </ul>
        </div>
    </div>

    <!-- Embarazo con Esperma Donado -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-pink-100">
            <i class="fas fa-heart text-pink-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Embarazo con Esperma Donado</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Tratamiento que utiliza espermatozoides de un donante anónimo. 
            Opción para mujeres solas, parejas con problemas de factor masculino o parejas del mismo sexo.
        </p>
        <div class="bg-pink-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-pink-800 mb-2 text-sm">¿En qué consiste?</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Selección del donante compatible</li>
                <li>• Inseminación artificial o FIV</li>
                <li>• Seguimiento médico personalizado</li>
                <li>• Apoyo psicológico durante el proceso</li>
            </ul>
        </div>
    </div>

    <!-- Método ROPA -->
    <div class="service-card text-center">
        <div class="service-icon mx-auto mb-4 bg-purple-100">
            <i class="fas fa-users text-purple-600"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Método ROPA</h3>
        <p class="text-gray-600 mb-4 text-sm leading-relaxed">
            Recepción de Óvulos de la Pareja diseñado para parejas de mujeres. 
            Una aporta los óvulos y la otra gesta el embarazo, permitiendo participación compartida.
        </p>
        <div class="bg-purple-50 p-3 rounded-lg text-left">
            <h4 class="font-semibold text-purple-800 mb-2 text-sm">¿En qué consiste?</h4>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Una pareja proporciona los óvulos</li>
                <li>• Fertilización con esperma de donante</li>
                <li>• La otra pareja recibe los embriones</li>
                <li>• Participación compartida en la maternidad</li>
            </ul>
        </div>
    </div>

</div>

@endsection
