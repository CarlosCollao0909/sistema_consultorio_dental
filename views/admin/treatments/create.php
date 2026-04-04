<h1 class="mb-6 text-3xl font-bold tracking-tight text-slate-800">Registrar Nuevo Tratamiento</h1>

<div class="my-6 flex justify-end">
    <a href="/admin/patients/profile?id=<?php echo $patientId; ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white shadow-md transition hover:bg-blue-700">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver al Perfil
    </a>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md md:p-8">
    <?php include_once __DIR__ . '/../../templates/alerts.php'; ?>

    <form action="/admin/treatments/create?patient_id=<?php echo $patientId; ?>" method="POST" class="space-y-6">
        <input type="hidden" name="patient_id" value="<?php echo $patientId; ?>">
        <?php include_once 'form.php'; ?>
        <input type="submit" value="Registrar Tratamiento" class="w-full cursor-pointer rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-700">
    </form>
</div>
