<!-- Modal de confirmación de baja de empleado -->
<div id="modal-baja-personal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
	<div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 relative border border-gray-200">
		<button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl focus:outline-none" onclick="closeModalBajaPersonal()">
			<i class="fas fa-times"></i>
		</button>
		<div class="flex items-center mb-4">
			<div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 mr-3">
				<i class="fas fa-user-slash text-red-600 text-xl"></i>
			</div>
			<h2 class="text-lg font-semibold text-gray-800">Confirmar baja de empleado</h2>
		</div>
		<div class="mb-4 px-2 py-2 bg-gray-50 rounded-lg border border-gray-100 flex items-center">
			<div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold mr-3">
				<span id="modal-user-initials">--</span>
			</div>
			<div>
				<p class="font-medium text-gray-900 mb-0" id="modal-user-nombre">Empleado</p>
				<p class="text-xs text-gray-500" id="modal-user-rol">Rol</p>
			</div>
		</div>
		<p class="text-gray-600 mb-4">¿Estás seguro que deseas dar de baja a este empleado? Esta acción puede revertirse luego.</p>
		<form id="form-baja-personal" method="POST" action="">
			@csrf
			<input type="hidden" name="user_id" id="modal-user-id" value="">
			<div class="flex justify-end gap-2 mt-4">
				<button type="button" class="btn-secondary px-3 py-1 text-sm" onclick="closeModalBajaPersonal()">Cancelar</button>
				<button type="submit" class="btn-primary px-3 py-1 text-sm">Confirmar baja</button>
			</div>
		</form>
	</div>
</div>

<script>
function openModalBajaPersonal(userId, actionUrl, nombre, apellido, rol) {
	document.getElementById('modal-baja-personal').classList.remove('hidden');
	document.getElementById('modal-user-id').value = userId;
	document.getElementById('form-baja-personal').action = actionUrl;
	document.getElementById('modal-user-nombre').textContent = nombre + ' ' + apellido;
	document.getElementById('modal-user-rol').textContent = rol;
	document.getElementById('modal-user-initials').textContent = (nombre[0] ? nombre[0].toUpperCase() : '-') + (apellido[0] ? apellido[0].toUpperCase() : '-');
}
function closeModalBajaPersonal() {
	document.getElementById('modal-baja-personal').classList.add('hidden');
}
</script>