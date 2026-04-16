<fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Información de la Especialidad</legend>

    <div class="mt-3 grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
            <label for="specialty_name" class="block text-sm font-medium text-slate-700">Nombre</label>
            <input
                type="text"
                name="specialty_name"
                id="specialty_name"
                data-format="titlecase"
                value="<?php echo sanitizeHTML($specialty->specialty_name); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. Ortodoncia, Endodoncia..."
            >
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-700">Descripción</label>
            <textarea
                name="description"
                id="description"
                data-format="sentencecase"
                rows="2"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Descripción breve de la especialidad, procedimientos comunes, etc..."
            ><?php echo sanitizeHTML($specialty->description); ?></textarea>
        </div>
    </div>
</fieldset>