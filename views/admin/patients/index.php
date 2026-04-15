<h1 class="text-3xl font-bold tracking-tight text-slate-800">Pacientes Registrados</h1>

<div class="my-6 flex justify-end">
    <a href="/admin/patients/create" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white shadow-md transition hover:bg-blue-700">
        <i class="fa-solid fa-circle-plus"></i>
        Nuevo Paciente
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md">
    <?php if (empty($patients)): ?>
        <p class="text-center text-gray-500 py-10 text-lg">No existen pacientes registrados</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto" id="dataTable">
                <thead>
                    <tr class="bg-slate-100 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <th class="py-3 px-3 text-left lg:px-6">Nombre</th>
                        <th class="py-3 px-3 text-left lg:px-6">Teléfono</th>
                        <th class="py-3 px-3 text-left lg:px-6">Edad</th>
                        <th class="hidden py-3 px-3 text-left lg:table-cell lg:px-6">Notas Médicas</th>
                        <th class="hidden py-3 px-3 text-left lg:table-cell lg:px-6">Alergias</th>
                        <th class="py-3 px-3 text-center lg:px-6">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    <?php foreach ($patients as $patient): ?>
                        <tr class="border-b border-slate-100 transition hover:bg-slate-50">
                            <td class="py-3 px-3 text-left whitespace-nowrap lg:px-6">
                                <div class="flex items-center">
                                    <span class="font-semibold text-slate-800"><?php echo $patient->name . ' ' . $patient->last_name; ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-left lg:px-6">
                                <span><?php echo $patient->phone; ?></span>
                            </td>
                            <td class="py-3 px-3 text-left lg:px-6">
                                <span><?php echo calculateAge($patient->birth_date) . ' años'; ?></span>
                            </td>
                            <td class="hidden py-3 px-3 text-left lg:table-cell lg:px-6">
                                <span><?php echo $patient->medical_notes ?: 'N/A'; ?></span>
                            </td>
                            <td class="hidden py-3 px-3 text-left lg:table-cell lg:px-6">
                                <span><?php echo $patient->allergies ?: 'N/A'; ?></span>
                            </td>
                            <td class="py-3 px-3 text-center lg:px-6">
                                <div class="flex flex-wrap items-center justify-center gap-2 lg:gap-4">
                                    <a href="/admin/patients/update?id=<?php echo $patient->id; ?>" class="inline-flex items-center gap-2 rounded-md bg-amber-100 px-3 py-1.5 font-medium text-amber-800 transition hover:bg-amber-200">
                                        <i class="fa-solid fa-pen"></i>
                                        <span class="hidden sm:inline">Editar</span>
                                    </a>
                                    <a href="/admin/patients/profile?id=<?php echo $patient->id; ?>" class="inline-flex items-center gap-2 rounded-md bg-green-100 px-3 py-1.5 font-medium text-green-800 transition hover:bg-green-200">
                                        <i class="fa-solid fa-eye"></i>
                                        <span class="hidden sm:inline">Ver Perfil</span>
                                    </a>
                                    <form method="POST" action="/admin/patients/delete" class="delete-form inline">
                                        <input type="hidden" name="id" value="<?php echo $patient->id; ?>">
                                        <button type="submit" class="inline-flex cursor-pointer items-center gap-2 rounded-md bg-rose-100 px-3 py-1.5 font-medium text-rose-700 transition hover:bg-rose-200">
                                            <i class="fa-solid fa-trash"></i>
                                            <span class="hidden sm:inline">Eliminar</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>