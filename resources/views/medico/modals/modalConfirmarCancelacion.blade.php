<!-- Modal de confirmación para cancelar tratamiento -->
<div x-show="confirmModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
     style="display: none;">
    <div 
        class="bg-white rounded-2xl w-full max-w-md shadow-2xl border border-gray-200"
        @click.outside="confirmModal = false"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <!-- Header del modal -->
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-900">Confirmar Cancelación</h2>
            </div>
            <button @click="confirmModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Formulario para enviar la cancelación -->
        <template x-if="tratamientoParaCancelar">
            <form :action="`{{ url('medico/paciente') }}/${tratamientoParaCancelar.id}/tratamiento/dar-de-baja`" 
                  method="POST" 
                  @submit="confirmModal = false">
            @csrf
            
            <!-- Contenido del modal -->
            <div class="px-6 py-4">
                <p class="text-gray-600 mb-2">¿Está seguro que desea cancelar este tratamiento?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-800">
                        <strong>Advertencia:</strong> Esta acción no se puede deshacer. El tratamiento quedará marcado como cancelado permanentemente.
                    </p>
                </div>
            </div>

            <!-- Input hidden con el ID del tratamiento -->
            <input type="hidden" name="tratamiento_id" :value="tratamientoParaCancelar?.id">

            <!-- Footer con botones -->
            <div class="flex justify-end space-x-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button 
                    type="button"
                    @click="confirmModal = false"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Cancelar
                </button>
                
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                >
                    Sí, cancelar tratamiento
                </button>
            </div>
            </form>
        </template>
    </div>
</div>
