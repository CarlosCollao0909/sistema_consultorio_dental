<fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Información del Paciente</legend>

    <div class="mt-3 grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-slate-700">Nombre</label>
            <input
                type="text"
                name="name"
                id="name"
                value="<?php echo sanitizeHTML($patient->name); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. María"
            >
        </div>

        <div class="space-y-2">
            <label for="last_name" class="block text-sm font-medium text-slate-700">Apellido</label>
            <input
                type="text"
                name="last_name"
                id="last_name"
                value="<?php echo sanitizeHTML($patient->last_name); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. López"
            >
        </div>

        <div class="space-y-2">
            <label for="phone" class="block text-sm font-medium text-slate-700">Teléfono</label>
            <input
                type="text"
                name="phone"
                id="phone"
                value="<?php echo sanitizeHTML($patient->phone); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. 61234567"
            >
        </div>

        <div class="space-y-2">
            <label for="birth_date" class="block text-sm font-medium text-slate-700">Fecha de Nacimiento</label>
            <input
                type="date"
                name="birth_date"
                id="birth_date"
                value="<?php echo sanitizeHTML($patient->birth_date); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="medical_notes" class="block text-sm font-medium text-slate-700">Notas Médicas</label>
            <textarea
                name="medical_notes"
                id="medical_notes"
                rows="4"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Antecedentes, tratamientos previos, observaciones relevantes..."
            ><?php echo sanitizeHTML($patient->medical_notes); ?></textarea>
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="allergies" class="block text-sm font-medium text-slate-700">Alergias</label>
            <textarea
                name="allergies"
                id="allergies"
                rows="3"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Medicamentos o materiales a evitar..."
            ><?php echo sanitizeHTML($patient->allergies); ?></textarea>
        </div>
    </div>
</fieldset>