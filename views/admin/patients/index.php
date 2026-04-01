<h1 class="text-2xl font-bold">Pacientes Registrados</h1>

<div class="flex justify-end my-6">
    <button id="addPatientButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center gap-2">Nuevo Paciente</button>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($patients)): ?>
        <p class="text-center text-gray-500 py-10 text-lg">No existen pacientes registrados</p>
    <?php else: ?>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Nombre</th>
                    <th class="py-3 px-6 text-left">Teléfono</th>
                    <th class="py-3 px-6 text-left">Edad</th>
                    <th class="py-3 px-6 text-left">Notas Médicas</th>
                    <th class="py-3 px-6 text-left">Alergias</th>
                    <th class="py-3 px-6 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($patients as $patient): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="font-medium"><?php echo $patient->name . ' ' . $patient->last_name; ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span><?php echo $patient->phone; ?></span>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span><?php echo calculateAge($patient->birth_date) . ' años'; ?></span>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span><?php echo $patient->medical_notes ?: 'N/A'; ?></span>
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span><?php echo $patient->allergies ?: 'N/A'; ?></span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center gap-4">
                                <button data-id="<?php echo $patient->id ?>" id="editPatientButton" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-1">
                                    Editar
                                </button>
                                <button data-id="<?php echo $patient->id ?>" id="deletePatientButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-1">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Create patient modal -->