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
                @if (Auth::check())
                    <a href="{{ route('paciente.solicitar-turno') }}"
                        class="btn-primary text-white px-8 py-3 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Solicitar Turno
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="btn-primary text-white px-8 py-3 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Solicitar Turno
                    </a>
                @endif
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
                <p class="text-gray-600 text-sm">Técnicas de reproducción asistida utilizando óvulos y espermatozoides de la
                    pareja.</p>
            </div>

            <div class="treatment-item bg-pink-50 rounded-lg p-6">
                <i class="fas fa-heart text-pink-600 text-3xl mb-3"></i>
                <h4 class="font-semibold text-gray-800 mb-2">Esperma Donado</h4>
                <p class="text-gray-600 text-sm">Tratamientos de fertilidad con donación de esperma de donantes anónimos.
                </p>
            </div>

            <div class="treatment-item bg-purple-50 rounded-lg p-6">
                <i class="fas fa-users text-purple-600 text-3xl mb-3"></i>
                <h4 class="font-semibold text-gray-800 mb-2">Método ROPA</h4>
                <p class="text-gray-600 text-sm">Recepción de óvulos de la pareja, tratamiento especializado para parejas
                    del mismo sexo.</p>
            </div>
        </div>
    </div>

    @if(auth()->check())
        <button id="openChatbotBtn" class="fixed bottom-6 right-6 z-50 p-4 bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 transition-all focus:outline-none" title="Abrir Chatbot">
        <i class="fas fa-robot text-white text-2xl"></i>
        </button>

        <div id="chatbotModal" class="hidden fixed bottom-[80px] right-6 w-full max-w-sm h-[80vh] max-h-[500px] bg-white rounded-xl shadow-2xl z-50 flex flex-col overflow-hidden border border-gray-200">
            
            <div class="p-4 bg-blue-600 text-white flex justify-between items-center shadow-md">
                <h5 class="text-lg font-semibold"><i class="fas fa-heartbeat mr-2"></i> Asistente de Fertilia</h5>
                <button id="closeChatbotBtn" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="chatHistory" class="flex-grow p-4 overflow-y-auto space-y-4">
                <div class="flex justify-center mb-4">
                    <div class="text-center text-sm text-gray-400 p-2 rounded-lg bg-gray-100">
                        ¡Hola! Soy tu asistente de fertilia. ¿En qué puedo ayudarte hoy?
                    </div>
                </div>
            </div>
            
            <form id="chatForm" class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex">
                    <input type="text" id="userInput" placeholder="Escribe tu mensaje..." required 
                        class="flex-grow p-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" id="sendBtn" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane" style="font-size: 15px;"></i>
                    </button>
                </div>
            </form>
        </div>
    @endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openChatbotBtn');
    const closeBtn = document.getElementById('closeChatbotBtn');
    const modal = document.getElementById('chatbotModal');
    const chatForm = document.getElementById('chatForm');
    const userInput = document.getElementById('userInput');
    const chatHistory = document.getElementById('chatHistory');
    const sendBtn = document.getElementById('sendBtn');

    // --- Lógica de Apertura y Cierre del Modal ---

    // Función para abrir el modal
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden'); // Muestra el modal
        openBtn.classList.add('hidden');  // Oculta el botón flotante
    });

    // Función para cerrar el modal
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');    // Oculta el modal
        openBtn.classList.remove('hidden'); // Muestra el botón flotante
    });
    
    // --- Lógica de la Conversación y Llamada a la API ---

    // Función para añadir un mensaje al historial visual (UI)
    function addMessageToChat(role, text) {
        const messageContainer = document.createElement('div');
        // Usamos clases de Tailwind para alinear y dar estilo de burbuja
        messageContainer.className = role === 'user' 
            ? 'flex justify-end' 
            : 'flex justify-start';

        const messageBubble = document.createElement('div');
        messageBubble.className = role === 'user'
            ? 'bg-blue-600 text-white p-3 rounded-t-xl rounded-bl-xl max-w-xs break-words'
            : 'bg-gray-200 text-gray-800 p-3 rounded-t-xl rounded-br-xl max-w-xs break-words';
        
        messageBubble.innerText = text;
        messageContainer.appendChild(messageBubble);
        chatHistory.appendChild(messageContainer);
        
        // Desplazar automáticamente al último mensaje
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    // Manejador del envío del formulario (la pregunta del usuario)
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const userMessage = userInput.value.trim();
        
        if (userMessage.length === 0) return;

        // 1. Mostrar el mensaje del usuario inmediatamente en la UI
        addMessageToChat('user', userMessage);
        
        // Deshabilitar la entrada y el botón durante la carga
        userInput.disabled = true;
        sendBtn.disabled = true;
        const originalIcon = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; // Muestra un spinner

        // Limpia el campo después de obtener el valor
        userInput.value = ''; 

        try {
            // 2. Llamar a la ruta de Laravel para contactar al chatbot
            const response = await fetch("{{ route('chatbot.send') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    // Obtiene el token CSRF para seguridad
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                // Envía el mensaje del usuario al controlador
                body: JSON.stringify({ message: userMessage })
            });
            
            const data = await response.json();

            if (response.ok) {
                // 3. Mostrar la respuesta exitosa del bot
                addMessageToChat('model', data.respuesta);
            } else {
                // Manejo de errores (ej: límite alcanzado o error interno del servidor)
                const errorMessage = data.error || "Ocurrió un error al procesar tu solicitud. Por favor, revisa los límites de uso.";
                addMessageToChat('model', `[Error]: ${errorMessage}`);
            }

        } catch (error) {
            console.error('Fetch error:', error);
            addMessageToChat('model', "[Error de conexión]: No se pudo conectar al servidor de Laravel o a la API externa.");
        } finally {
            // Restablecer la interfaz
            userInput.disabled = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalIcon; // Restaura el icono de enviar
            userInput.focus(); // Vuelve a enfocar el campo para la siguiente pregunta
        }
    });
});
</script>
@endsection
