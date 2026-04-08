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
        <a href="/admin/treatments/create?patient_id=<?php echo $patient->id; ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
            <i class="fa-solid fa-plus"></i>
            Nuevo Tratamiento
        </a>
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
                                <a
                                    href="/admin/treatments/update?id=<?php echo $treatment->id; ?>"
                                    class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-3 py-1.5 text-sm font-medium text-amber-800 transition hover:bg-amber-200">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="/admin/treatments/delete" class="delete-form inline">
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
                                Costo: Bs. <?php echo number_format($treatment->total_cost, 2); ?>
                            </span>
                            <span class="rounded-md bg-emerald-50 px-3 py-1 font-medium text-emerald-700">
                                Pagado: Bs. <?php echo number_format($treatment->total_paid, 2); ?>
                            </span>
                            <span class="rounded-md <?php echo $treatment->balance > 0 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700'; ?> px-3 py-1 font-medium">
                                Pendiente: Bs. <?php echo number_format($treatment->balance, 2); ?>
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
                            <a
                                href="/admin/appointments/create?patient_id=<?php echo $patient->id; ?>&treatment_id=<?php echo $treatment->id; ?>"
                                class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                                <i class="fa-solid fa-plus"></i>
                                Nueva Cita
                            </a>
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
                                            <a
                                                href="/admin/appointments/update?id=<?php echo $appointment->id; ?>"
                                                class="inline-flex items-center rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-800 transition hover:bg-amber-200">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
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
                                <a
                                    href="/admin/payments/create?patient_id=<?php echo $patient->id; ?>&treatment_id=<?php echo $treatment->id; ?>"
                                    class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">
                                    <i class="fa-solid fa-plus"></i>
                                    Nuevo Pago
                                </a>
                            <?php endif; ?>
                        </div>
                        <div id="payments-<?php echo $treatment->id; ?>" class="hidden space-y-2">
                            <?php if (empty($treatment->payments)): ?>
                                <p class="text-sm text-slate-500">No hay pagos registrados</p>
                            <?php else: ?>
                                <?php foreach ($treatment->payments as $payment): ?>
                                    <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-emerald-700">Bs. <?php echo number_format($payment->amount_paid, 2); ?></span>
                                            <span class="text-slate-600"><?php echo formatTimestamp($payment->payment_date); ?></span>
                                        </div>
                                        <form method="POST" action="/admin/payments/delete" class="delete-form inline">
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
        <a href="/admin/attachments/create?patient_id=<?php echo $patient->id; ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white shadow-md transition hover:bg-blue-700">
            <i class="fa-solid fa-upload"></i>
            Subir PDF
        </a>
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
                                <p class="text-xs text-slate-500">Subido el <?php echo formatTimestamp($attachment->uploaded_at, true); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="/admin/attachments/download?id=<?php echo $attachment->id; ?>" class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <a href="/admin/attachments/show?id=<?php echo $attachment->id; ?>" class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100" target="_blank">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <form method="POST" action="/admin/attachments/delete" class="delete-form inline">
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