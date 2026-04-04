<?php
$statusLabels = [
    'pendiente' => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-800'],
    'en_progreso' => ['label' => 'En progreso', 'class' => 'bg-blue-100 text-blue-800'],
    'completado' => ['label' => 'Completado', 'class' => 'bg-emerald-100 text-emerald-800'],
    'cancelado' => ['label' => 'Cancelado', 'class' => 'bg-rose-100 text-rose-700'],
];

$appointmentStatusLabels = [
    'programada' => ['label' => 'Programada', 'class' => 'bg-blue-100 text-blue-800'],
    'completada' => ['label' => 'Completada', 'class' => 'bg-emerald-100 text-emerald-800'],
    'cancelada' => ['label' => 'Cancelada', 'class' => 'bg-rose-100 text-rose-700'],
    'no_asistio' => ['label' => 'No asistió', 'class' => 'bg-slate-100 text-slate-600'],
];
?>

<!-- Header -->
<div class="mb-6 flex items-center justify-between">
    <a href="/admin/patients" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200">
        <i class="fa-solid fa-arrow-left"></i>
        Volver a Pacientes
    </a>
</div>

<!-- Patient Info Card -->
<div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-md">
    <h1 class="mb-4 text-2xl font-bold text-slate-800">
        <i class="fa-solid fa-user-circle mr-2 text-blue-600"></i>
        <?php echo sanitizeHTML($patient->name . ' ' . $patient->last_name); ?>
    </h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Teléfono</span>
            <p class="mt-1 text-slate-800"><?php echo sanitizeHTML($patient->phone); ?></p>
        </div>
        <div>
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Edad</span>
            <p class="mt-1 text-slate-800"><?php echo calculateAge($patient->birth_date); ?> años</p>
        </div>
        <div>
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notas Médicas</span>
            <p class="mt-1 text-slate-800"><?php echo sanitizeHTML($patient->medical_notes) ?: 'N/A'; ?></p>
        </div>
        <div>
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alergias</span>
            <p class="mt-1 text-slate-800"><?php echo sanitizeHTML($patient->allergies) ?: 'N/A'; ?></p>
        </div>
    </div>
</div>

<!-- Treatments Section -->
<div class="mb-8">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fa-solid fa-tooth mr-2 text-blue-600"></i>
            Tratamientos
        </h2>
        <button onclick="openModal('treatment-modal')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
            <i class="fa-solid fa-plus"></i>
            Nuevo Tratamiento
        </button>
    </div>

    <?php if (empty($treatments)): ?>
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-md">
            <p class="text-lg text-slate-500">No hay tratamientos registrados para este paciente</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($treatments as $treatment): ?>
                <?php
                    $tstatus = $statusLabels[$treatment->status] ?? $statusLabels['pendiente'];
                ?>
                <div class="rounded-xl border border-slate-200 bg-white shadow-md">
                    <!-- Treatment Header -->
                    <div class="border-b border-slate-100 p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800"><?php echo sanitizeHTML($treatment->treatment_name); ?></h3>
                                <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                                    <span><i class="fa-solid fa-stethoscope mr-1"></i><?php echo sanitizeHTML($treatment->specialty_name); ?></span>
                                    <span><i class="fa-solid fa-user-doctor mr-1"></i><?php echo sanitizeHTML($treatment->doctor_name); ?></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?php echo $tstatus['class']; ?>">
                                    <?php echo $tstatus['label']; ?>
                                </span>
                                <button
                                    onclick="openEditTreatmentModal(this)"
                                    data-id="<?php echo $treatment->id; ?>"
                                    data-user-id="<?php echo $treatment->user_id; ?>"
                                    data-specialty-id="<?php echo $treatment->specialty_id; ?>"
                                    data-treatment-name="<?php echo sanitizeHTML($treatment->treatment_name); ?>"
                                    data-total-cost="<?php echo $treatment->total_cost; ?>"
                                    data-status="<?php echo $treatment->status; ?>"
                                    class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-3 py-1.5 text-sm font-medium text-amber-800 transition hover:bg-amber-200">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="/admin/patients/profile/treatment-delete" class="delete-form inline">
                                    <input type="hidden" name="id" value="<?php echo $treatment->id; ?>">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                    <button type="submit" class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-rose-100 px-3 py-1.5 text-sm font-medium text-rose-700 transition hover:bg-rose-200">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Cost / Balance Summary -->
                        <div class="mt-3 flex flex-wrap gap-4 text-sm">
                            <span class="rounded-md bg-slate-100 px-3 py-1 font-medium text-slate-700">
                                Costo: $<?php echo number_format($treatment->total_cost, 2); ?>
                            </span>
                            <span class="rounded-md bg-emerald-50 px-3 py-1 font-medium text-emerald-700">
                                Pagado: $<?php echo number_format($treatment->total_paid, 2); ?>
                            </span>
                            <span class="rounded-md <?php echo $treatment->balance > 0 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700'; ?> px-3 py-1 font-medium">
                                Pendiente: $<?php echo number_format($treatment->balance, 2); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Appointments Sub-section -->
                    <div class="border-b border-slate-100 p-5">
                        <div class="mb-3 flex items-center justify-between">
                            <button onclick="toggleSection('appointments-<?php echo $treatment->id; ?>')" class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-blue-600">
                                <i class="fa-solid fa-chevron-down toggle-icon transition-transform" id="icon-appointments-<?php echo $treatment->id; ?>"></i>
                                <i class="fa-solid fa-calendar-check text-blue-500"></i>
                                Citas (<?php echo count($treatment->appointments); ?>)
                            </button>
                            <button
                                onclick="openAppointmentModal(<?php echo $treatment->id; ?>)"
                                class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                                <i class="fa-solid fa-plus"></i>
                                Nueva Cita
                            </button>
                        </div>
                        <div id="appointments-<?php echo $treatment->id; ?>" class="hidden space-y-2">
                            <?php if (empty($treatment->appointments)): ?>
                                <p class="text-sm text-slate-500">No hay citas registradas</p>
                            <?php else: ?>
                                <?php foreach ($treatment->appointments as $appointment): ?>
                                    <?php $astatus = $appointmentStatusLabels[$appointment->status] ?? $appointmentStatusLabels['programada']; ?>
                                    <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm">
                                        <div class="flex items-center gap-3">
                                            <span class="font-medium text-slate-800"><?php echo $appointment->date; ?></span>
                                            <span class="text-slate-600"><?php echo substr($appointment->time, 0, 5); ?></span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold <?php echo $astatus['class']; ?>">
                                                <?php echo $astatus['label']; ?>
                                            </span>
                                            <?php if ($appointment->observations): ?>
                                                <span class="text-slate-500" title="<?php echo sanitizeHTML($appointment->observations); ?>">
                                                    <i class="fa-solid fa-comment-dots"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button
                                                onclick="openEditAppointmentModal(this)"
                                                data-id="<?php echo $appointment->id; ?>"
                                                data-treatment-id="<?php echo $appointment->treatment_id; ?>"
                                                data-date="<?php echo $appointment->date; ?>"
                                                data-time="<?php echo $appointment->time; ?>"
                                                data-status="<?php echo $appointment->status; ?>"
                                                data-observations="<?php echo sanitizeHTML($appointment->observations); ?>"
                                                class="inline-flex items-center rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-800 transition hover:bg-amber-200">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <form method="POST" action="/admin/patients/profile/appointment-delete" class="delete-form inline">
                                                <input type="hidden" name="id" value="<?php echo $appointment->id; ?>">
                                                <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                                <button type="submit" class="inline-flex cursor-pointer items-center rounded-md bg-rose-100 px-2 py-1 text-xs font-medium text-rose-700 transition hover:bg-rose-200">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payments Sub-section -->
                    <div class="p-5">
                        <div class="mb-3 flex items-center justify-between">
                            <button onclick="toggleSection('payments-<?php echo $treatment->id; ?>')" class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-blue-600">
                                <i class="fa-solid fa-chevron-down toggle-icon transition-transform" id="icon-payments-<?php echo $treatment->id; ?>"></i>
                                <i class="fa-solid fa-money-bill-wave text-emerald-500"></i>
                                Pagos (<?php echo count($treatment->payments); ?>)
                            </button>
                            <?php if ($treatment->balance > 0): ?>
                                <button
                                    onclick="openPaymentModal(<?php echo $treatment->id; ?>, <?php echo $treatment->balance; ?>)"
                                    class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">
                                    <i class="fa-solid fa-plus"></i>
                                    Nuevo Pago
                                </button>
                            <?php endif; ?>
                        </div>
                        <div id="payments-<?php echo $treatment->id; ?>" class="hidden space-y-2">
                            <?php if (empty($treatment->payments)): ?>
                                <p class="text-sm text-slate-500">No hay pagos registrados</p>
                            <?php else: ?>
                                <?php foreach ($treatment->payments as $payment): ?>
                                    <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-emerald-700">$<?php echo number_format($payment->amount_paid, 2); ?></span>
                                            <span class="text-slate-600"><?php echo $payment->payment_date; ?></span>
                                        </div>
                                        <form method="POST" action="/admin/patients/profile/payment-delete" class="delete-form inline">
                                            <input type="hidden" name="id" value="<?php echo $payment->id; ?>">
                                            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                            <button type="submit" class="inline-flex cursor-pointer items-center rounded-md bg-rose-100 px-2 py-1 text-xs font-medium text-rose-700 transition hover:bg-rose-200">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Attachments Section -->
<div class="mb-8">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fa-solid fa-file-pdf mr-2 text-rose-500"></i>
            Archivos Adjuntos
        </h2>
        <button onclick="openModal('attachment-modal')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
            <i class="fa-solid fa-upload"></i>
            Subir PDF
        </button>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-md">
        <?php if (empty($attachments)): ?>
            <p class="p-8 text-center text-lg text-slate-500">No hay archivos adjuntos</p>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($attachments as $attachment): ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-pdf text-lg text-rose-500"></i>
                            <div>
                                <p class="font-medium text-slate-800"><?php echo sanitizeHTML($attachment->file_name); ?></p>
                                <p class="text-xs text-slate-500"><?php echo $attachment->uploaded_at; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="/admin/patients/profile/attachment-download?id=<?php echo $attachment->id; ?>" class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                                <i class="fa-solid fa-download"></i>
                                Descargar
                            </a>
                            <form method="POST" action="/admin/patients/profile/attachment-delete" class="delete-form inline">
                                <input type="hidden" name="id" value="<?php echo $attachment->id; ?>">
                                <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                <button type="submit" class="inline-flex cursor-pointer items-center gap-1 rounded-md bg-rose-100 px-3 py-1.5 text-sm font-medium text-rose-700 transition hover:bg-rose-200">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== MODALS ==================== -->

<!-- Treatment Modal (Create/Edit) -->
<div id="treatment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 id="treatment-modal-title" class="text-lg font-bold text-slate-800">Nuevo Tratamiento</h3>
            <button onclick="closeModal('treatment-modal')" class="text-slate-400 transition hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="treatment-form" method="POST" action="/admin/patients/profile/treatment-create">
            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
            <input type="hidden" name="id" id="treatment-id" value="">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nombre del tratamiento</label>
                    <input type="text" name="treatment_name" id="treatment-treatment_name" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Doctor</label>
                        <select name="user_id" id="treatment-user_id" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo $doctor->id; ?>"><?php echo sanitizeHTML($doctor->name . ' ' . $doctor->last_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Especialidad</label>
                        <select name="specialty_id" id="treatment-specialty_id" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                            <option value="">Seleccionar...</option>
                            <?php foreach ($specialties as $specialty): ?>
                                <option value="<?php echo $specialty->id; ?>"><?php echo sanitizeHTML($specialty->specialty_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Costo total</label>
                        <input type="number" name="total_cost" id="treatment-total_cost" required min="0.01" step="0.01"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Estado</label>
                        <select name="status" id="treatment-status"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                            <option value="pendiente">Pendiente</option>
                            <option value="en_progreso">En progreso</option>
                            <option value="completado">Completado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('treatment-modal')" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Appointment Modal (Create/Edit) -->
<div id="appointment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 id="appointment-modal-title" class="text-lg font-bold text-slate-800">Nueva Cita</h3>
            <button onclick="closeModal('appointment-modal')" class="text-slate-400 transition hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="appointment-form" method="POST" action="/admin/patients/profile/appointment-create">
            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
            <input type="hidden" name="id" id="appointment-id" value="">
            <input type="hidden" name="treatment_id" id="appointment-treatment_id" value="">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Fecha</label>
                        <input type="date" name="date" id="appointment-date" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Hora</label>
                        <input type="time" name="time" id="appointment-time" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Estado</label>
                    <select name="status" id="appointment-status"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                        <option value="programada">Programada</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="no_asistio">No asistió</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Observaciones</label>
                    <textarea name="observations" id="appointment-observations" rows="3"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('appointment-modal')" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Payment Modal -->
<div id="payment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Nuevo Pago</h3>
            <button onclick="closeModal('payment-modal')" class="text-slate-400 transition hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="payment-form" method="POST" action="/admin/patients/profile/payment-create">
            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
            <input type="hidden" name="treatment_id" id="payment-treatment_id" value="">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Balance pendiente</label>
                    <p id="payment-balance-display" class="text-lg font-semibold text-rose-600"></p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Monto a pagar</label>
                    <input type="number" name="amount_paid" id="payment-amount_paid" required min="0.01" step="0.01"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('payment-modal')" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-emerald-700">
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Attachment Modal -->
<div id="attachment-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Subir Archivo PDF</h3>
            <button onclick="closeModal('attachment-modal')" class="text-slate-400 transition hover:text-slate-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" action="/admin/patients/profile/attachment-upload" enctype="multipart/form-data">
            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Archivo PDF (máx. 5MB)</label>
                    <input type="file" name="attachment" accept=".pdf" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 focus:outline-none">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('attachment-modal')" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
                    Subir
                </button>
            </div>
        </form>
    </div>
</div>