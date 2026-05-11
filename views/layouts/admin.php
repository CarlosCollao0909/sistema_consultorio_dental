<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/main.css?v=<?php echo APP_VERSION; ?>">
    <title>Consultorio Dental Perfect Dent</title>
</head>

<body>
    <?php
    $fullName = $_SESSION['name'] ?? '';
    $firstName = $fullName ? explode(' ', trim($fullName))[0] : 'Usuario';
    ?>

    <div class="flex h-screen bg-slate-100">
        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 transition-opacity duration-300 lg:hidden hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full overflow-y-auto border-r border-slate-800 bg-slate-900 text-white shadow-lg transition-transform duration-300 lg:translate-x-0">
            <div class="flex items-center justify-between border-b border-slate-800 p-6">
                <h1 class="inline-flex items-center gap-2 text-2xl font-bold tracking-tight">
                    <i class="fa-solid fa-tooth"></i>
                    Perfect Dent
                </h1>
                <button id="sidebar-close" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white lg:hidden">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <nav class="mt-4 space-y-1 px-3">
                <a href="/admin/dashboard"
                    class="sidebar-link inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/dashboard') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-chart-line w-4"></i>Dashboard</a>
                <a href="/admin/appointments"
                    class="sidebar-link inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/appointments') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-calendar-check w-4"></i>Citas</a>
                <a href="/admin/patients"
                    class="sidebar-link inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/patients') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-user-group w-4"></i>Pacientes</a>
                <a href="/admin/specialties"
                    class="sidebar-link inline-flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-white transition hover:bg-slate-800 hover:text-white <?php echo currentPage('/specialties') ? 'bg-blue-700/70 shadow-sm' : ''; ?>"><i class="fa-solid fa-stethoscope w-4"></i>Especialidades</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col lg:ml-72">
            <!-- Header -->
            <header class="sticky top-0 z-10 border-b border-white bg-white/90 shadow-sm backdrop-blur">
                <div class="flex items-center justify-between px-4 py-3 lg:px-8 lg:py-4">
                    <div class="inline-flex items-center gap-3">
                        <button id="sidebar-toggle" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 lg:hidden">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <i class="fa-regular fa-heart"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight text-slate-800 lg:text-xl">Hola, <?php echo sanitizeHTML($firstName); ?></h2>
                            <p class="hidden text-sm text-slate-500 sm:block">Bienvenido al sistema</p>
                        </div>
                    </div>
                    <a href="/logout" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 font-medium text-rose-700 transition hover:bg-rose-100">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Cerrar sesión</span>
                    </a>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-auto p-4 lg:p-8" id="goUp">
                <?php echo $content; ?>
            </main>
            <a href="javascript:void(0)" title="Ir Arriba" id="goUpBtn"
                class="pointer-events-none fixed bottom-4 right-4 rounded-full bg-slate-800 p-3 text-white opacity-0 shadow-lg transition-all duration-300 ease-in-out hover:bg-slate-700">
                <i class="fa-solid fa-arrow-up"></i>
            </a>
        </div>
    </div>
    <script src="/build/js/admin.js?v=<?php echo APP_VERSION; ?>"></script>
</body>

</html>