<h1 class="mb-6 text-3xl font-bold tracking-tight text-slate-800">Registrar Nuevo Pago</h1>

<div class="my-6 flex justify-end">
    <a href="/admin/patients/profile?id=<?php echo $patientId; ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white shadow-md transition hover:bg-blue-700">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver al Perfil
    </a>
</div>

<div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
    <i class="fa-solid fa-tooth mr-1"></i>
    Tratamiento: <strong><?php echo sanitizeHTML($treatment->treatment_name); ?></strong>
    <span class="mx-2">|</span>
    <i class="fa-solid fa-money-bill-wave mr-1"></i>
    Balance pendiente: <strong class="text-rose-600">$<?php echo number_format($balance, 2); ?></strong>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md md:p-8">
    <?php include_once __DIR__ . '/../../templates/alerts.php'; ?>

    <form action="/admin/payments/create?patient_id=<?php echo $patientId; ?>&treatment_id=<?php echo $treatment->id; ?>" method="POST" class="space-y-6">
        <input type="hidden" name="patient_id" value="<?php echo $patientId; ?>">
        <input type="hidden" name="treatment_id" value="<?php echo $treatment->id; ?>">

        <fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
            <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Información del Pago</legend>

            <div class="mt-3 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="amount_paid" class="block text-sm font-medium text-slate-700">Monto a Pagar</label>
                    <input
                        type="number"
                        name="amount_paid"
                        id="amount_paid"
                        value="<?php echo sanitizeHTML($payment->amount_paid); ?>"
                        min="0.01"
                        step="0.01"
                        max="<?php echo $balance; ?>"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        placeholder="Ej. 500.00"
                    >
                </div>
            </div>
        </fieldset>

        <input type="submit" value="Registrar Pago" class="w-full cursor-pointer rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-700">
    </form>
</div>
