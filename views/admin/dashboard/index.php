<section class="space-y-6">
    <!-- Header -->
    <div class="space-y-1">
        <h1 class="text-3xl font-bold tracking-tight text-slate-800">Dashboard</h1>
        <p class="text-sm text-slate-500">Resumen general de tu consultorio.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Pacientes Activos -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Pacientes Activos</p>
                    <p id="kpi-patients" class="text-2xl font-bold text-slate-800">—</p>
                </div>
            </div>
        </div>
        <!-- Citas de Hoy -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Citas de Hoy</p>
                    <p id="kpi-today" class="text-2xl font-bold text-slate-800">—</p>
                </div>
            </div>
        </div>
        <!-- Ingresos del Mes -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Ingresos del Mes</p>
                    <p id="kpi-revenue" class="text-2xl font-bold text-slate-800">—</p>
                </div>
            </div>
        </div>
        <!-- Tratamientos Activos -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                    <i class="fa-solid fa-teeth"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Tratamientos Activos</p>
                    <p id="kpi-treatments" class="text-2xl font-bold text-slate-800">—</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Ingresos Mensuales -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-800">Ingresos Mensuales</h2>
            <canvas id="chart-revenue"></canvas>
        </div>
        <!-- Citas por Estado -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-800">Citas por Estado</h2>
            <div class="mx-auto" style="max-width: 300px;">
                <canvas id="chart-appointments-status"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Tratamientos por Especialidad -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-800">Tratamientos por Especialidad</h2>
            <div class="mx-auto" style="max-width: 300px;">
                <canvas id="chart-specialties"></canvas>
            </div>
        </div>
    </div>
</section>