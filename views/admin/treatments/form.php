<fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Información del Tratamiento</legend>

    <div class="mt-3 grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
            <label for="treatment_name" class="block text-sm font-medium text-slate-700">Nombre del Tratamiento</label>
            <input
                type="text"
                name="treatment_name"
                id="treatment_name"
                value="<?php echo sanitizeHTML($treatment->treatment_name); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. Limpieza dental, Extracción..."
            >
        </div>

        <div class="space-y-2">
            <label for="user_id" class="block text-sm font-medium text-slate-700">Doctor</label>
            <select
                name="user_id"
                id="user_id"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
                <option value="">Seleccionar doctor...</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo $doctor->id; ?>" <?php echo $treatment->user_id == $doctor->id ? 'selected' : ''; ?>>
                        <?php echo sanitizeHTML($doctor->name . ' ' . $doctor->last_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="space-y-2">
            <label for="specialty_id" class="block text-sm font-medium text-slate-700">Especialidad</label>
            <select
                name="specialty_id"
                id="specialty_id"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
                <option value="">Seleccionar especialidad...</option>
                <?php foreach ($specialties as $specialty): ?>
                    <option value="<?php echo $specialty->id; ?>" <?php echo $treatment->specialty_id == $specialty->id ? 'selected' : ''; ?>>
                        <?php echo sanitizeHTML($specialty->specialty_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="space-y-2">
            <label for="total_cost" class="block text-sm font-medium text-slate-700">Costo Total</label>
            <input
                type="number"
                name="total_cost"
                id="total_cost"
                value="<?php echo sanitizeHTML($treatment->total_cost); ?>"
                min="0.01"
                step="0.01"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Ej. 2500.00"
            >
        </div>

        <div class="space-y-2">
            <label for="status" class="block text-sm font-medium text-slate-700">Estado</label>
            <select
                name="status"
                id="status"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
                <option value="pendiente" <?php echo $treatment->status === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_progreso" <?php echo $treatment->status === 'en_progreso' ? 'selected' : ''; ?>>En progreso</option>
                <option value="completado" <?php echo $treatment->status === 'completado' ? 'selected' : ''; ?>>Completado</option>
                <option value="cancelado" <?php echo $treatment->status === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
            </select>
        </div>
    </div>
</fieldset>
