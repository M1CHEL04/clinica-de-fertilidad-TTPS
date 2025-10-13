@extends('layouts.layoutUsuario')

@section('title', 'Inicio')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center mb-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-center mb-4">
            <i class="fas fa-heart text-pink-400 text-4xl mr-3"></i>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Clínica de Fertilidad</h1>
        </div>
        
        <p class="text-xl text-gray-600 mb-2 font-medium">"Juntos, creamos futuros"</p>
        
        <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
            Nos dedicamos a acompañar a las personas y parejas en su camino hacia la maternidad y la paternidad, 
            brindando tratamientos de fertilidad, técnicas de reproducción asistida y apoyo integral para cumplir 
            el sueño de formar una familia.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="#" class="btn-primary text-white px-8 py-3 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-calendar-plus mr-2"></i>
                Solicitar Turno
            </a>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="grid md:grid-cols-3 gap-8 mb-12">
    <!-- Ayuda Médica -->
    <div class="service-card group">
        <div class="service-icon">
            <i class="fas fa-user-md"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Ayuda Médica Especializada</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
            Contamos con profesionales especializados en fertilidad que te acompañarán 
            durante todo el proceso con atención personalizada y de calidad.
        </p>
    </div>
    
    <!-- Tratamientos Avanzados -->
    <div class="service-card group">
        <div class="service-icon">
            <i class="fas fa-microscope"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Tratamientos Avanzados</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
            Ofrecemos las técnicas más modernas y efectivas en reproducción asistida, 
            adaptadas a las necesidades específicas de cada paciente.
        </p>
    </div>
    
    <!-- Preservación de Gametos -->
    <div class="service-card group">
        <div class="service-icon">
            <i class="fas fa-snowflake"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Preservación de Gametos</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
            Servicios de criopreservación para aquellos que desean preservar su 
            fertilidad para el futuro con tecnología de última generación.
        </p>
    </div>
</div>

<!-- Treatments Summary -->
<div class="bg-white rounded-2xl shadow-lg p-8 text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Nuestros Tratamientos</h2>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="treatment-item bg-blue-50 rounded-lg p-6">
            <i class="fas fa-baby text-blue-600 text-3xl mb-3"></i>
            <h4 class="font-semibold text-gray-800 mb-2">Embarazo con Gametos Propios</h4>
            <p class="text-gray-600 text-sm">Fertilización in vitro utilizando óvulos y espermatozoides de la pareja.</p>
        </div>
        
        <div class="treatment-item bg-pink-50 rounded-lg p-6">
            <i class="fas fa-heart text-pink-600 text-3xl mb-3"></i>
            <h4 class="font-semibold text-gray-800 mb-2">Esperma Donado</h4>
            <p class="text-gray-600 text-sm">Tratamientos de fertilidad con donación de esperma de donantes anónimos.</p>
        </div>
        
        <div class="treatment-item bg-purple-50 rounded-lg p-6">
            <i class="fas fa-users text-purple-600 text-3xl mb-3"></i>
            <h4 class="font-semibold text-gray-800 mb-2">Método ROPA</h4>
            <p class="text-gray-600 text-sm">Recepción de óvulos de la pareja, tratamiento especializado para parejas del mismo sexo.</p>
        </div>
    </div>
</div>
@endsection