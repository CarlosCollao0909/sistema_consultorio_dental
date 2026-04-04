<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/main.css">
    <title>Consultorio Dental PerfectDent</title>
</head>

<body>
    <?php
    $fullName = $_SESSION['name'] ?? '';
    $firstName = $fullName ? explode(' ', trim($fullName))[0] : 'Usuario';
    ?>

    <div class="flex min-h-screen bg-slate-100">
        <!-- Sidebar -->
        <aside class="w-72 border-r border-slate-800 bg-slate-900 text-white shadow-lg">
            <div class="border-b border-slate-800 p-6">
                <h1 class="inline-flex items-center gap-2 text-2xl font-bold tracking-tight">
                    <i class="fa-solid fa-tooth"></i>
                    PerfectDent
                </h1>
                <p class="mt-1 text-sm text-slate-300">Consultorio Odontológico</p>
            </div>
            <nav class="mt-4 space-y-1 px-3">
                <a href="/admin/dashboard"
                    class="inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/dashboard') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-chart-line w-4"></i>Dashboard</a>
                <a href="/admin/patients"
                    class="inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/patients') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-user-group w-4"></i>Pacientes</a>
                <a href="/admin/appointments"
                    class="inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/appointments') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-calendar-check w-4"></i>Citas</a>
                <a href="/admin/specialties"
                    class="inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/specialties') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-stethoscope w-4"></i>Especialidades</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col">
            <!-- Header -->
            <header class="border-b border-white bg-white/90 shadow-sm backdrop-blur">
                <div class="flex items-center justify-between px-8 py-4">
                    <div class="inline-flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <i class="fa-regular fa-heart"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold tracking-tight text-slate-800">Hola, <?php echo sanitizeHTML($firstName); ?></h2>
                            <p class="text-sm text-slate-500">Bienvenido al sistema</p>
                        </div>
                    </div>
                    <a href="/logout" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 font-medium text-rose-700 transition hover:bg-rose-100">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Cerrar sesión
                    </a>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-auto p-8">
                <?php echo $content; ?>
            </main>
        </div>
    </div>
    <script src="/build/js/admin.js"></script>
</body>

</html>