<h1 class="mb-6 text-3xl font-bold tracking-tight text-slate-800">Subir Archivo PDF</h1>

<div class="my-6 flex justify-end">
    <a href="/admin/patients/profile?id=<?php echo $patientId; ?>" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white shadow-md transition hover:bg-blue-700">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver al Perfil
    </a>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md md:p-8">
    <?php include_once __DIR__ . '/../../templates/alerts.php'; ?>

    <form action="/admin/attachments/create?patient_id=<?php echo $patientId; ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="patient_id" value="<?php echo $patientId; ?>">

        <fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
            <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Archivo Adjunto</legend>

            <div class="mt-3">
                <div class="space-y-2">
                    <label for="attachment" class="block text-sm font-medium text-slate-700">Archivo PDF (máx. 5MB)</label>
                    <input
                        type="file"
                        name="attachment"
                        id="attachment"
                        accept=".pdf"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >
                </div>
            </div>
        </fieldset>

        <input type="submit" value="Subir Archivo" class="w-full cursor-pointer rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-700">
    </form>
</div>
