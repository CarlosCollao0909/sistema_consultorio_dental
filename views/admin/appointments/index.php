<section class="space-y-3">
    <div class="space-y-1">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">Calendario de Citas</h1>
        <p class="text-sm text-slate-500">Selecciona una fecha para ver el detalle de citas agendadas.</p>
    </div>
</section>

<div class="my-6 grid grid-cols-1 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md md:grid-cols-2">
    <div class="border-b border-slate-100 bg-slate-50/60 p-3 sm:p-6 md:border-b-0 md:border-r" id="calendar-container">
        <div id="calendar" class="max-w-full overflow-hidden"></div>
    </div>
    <div class="bg-white p-3 sm:p-6">
        <div id="appointments" class="appointments-panel"></div>
    </div>
</div>