<fieldset class="rounded-xl border border-slate-200 bg-slate-50/70 p-5 md:p-6">
    <legend class="px-2 text-sm font-semibold uppercase tracking-wide text-slate-700">Información de la Cita</legend>

    <div class="mt-3 grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="date" class="block text-sm font-medium text-slate-700">Fecha</label>
            <input
                type="date"
                name="date"
                id="date"
                value="<?php echo sanitizeHTML($appointment->date); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
        </div>

        <div class="space-y-2">
            <label for="time" class="block text-sm font-medium text-slate-700">Hora</label>
            <input
                type="time"
                name="time"
                id="time"
                value="<?php echo sanitizeHTML($appointment->time); ?>"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
        </div>

        <div class="space-y-2">
            <label for="status" class="block text-sm font-medium text-slate-700">Estado</label>
            <select
                name="status"
                id="status"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
                <option value="pending" <?php echo $appointment->status === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="completed" <?php echo $appointment->status === 'completed' ? 'selected' : ''; ?>>Completada</option>
                <option value="canceled" <?php echo $appointment->status === 'canceled' ? 'selected' : ''; ?>>Cancelada</option>
            </select>
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="observations" class="block text-sm font-medium text-slate-700">Observaciones</label>
            <textarea
                name="observations"
                id="observations"
                data-format="sentencecase"
                rows="3"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                placeholder="Notas o indicaciones para la cita..."
            ><?php echo sanitizeHTML($appointment->observations); ?></textarea>
        </div>
    </div>
</fieldset>
