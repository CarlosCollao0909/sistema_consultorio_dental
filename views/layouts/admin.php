<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/main.css">
    <title>Consultorio Dental PerfectDent</title>
</head>

<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white shadow-lg">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold">PerfectDent</h1>
            </div>
            <nav class="mt-6">
                <a href="/admin/dashboard"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/dashboard') ? 'bg-gray-800' : ''; ?>">Dashboard</a>
                <a href="/admin/patients"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/patients') ? 'bg-gray-800' : ''; ?>">Pacientes</a>
                <a href="/admin/appointments"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/appointments') ? 'bg-gray-800' : ''; ?>">Citas</a>
                <a href="/admin/specialties"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/specialties') ? 'bg-gray-800' : ''; ?>">Especialidades</a>
                <a href="/admin/treatments"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/treatments') ? 'bg-gray-800' : ''; ?>">Tratamientos</a>
                <a href="/admin/payments"
                    class="block px-6 py-3 hover:bg-gray-800 transition <?php echo currentPage('/payments') ? 'bg-gray-800' : ''; ?>">Configuración</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="px-8 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Panel de Administración</h2>
                    <div class="group flex flex-col items-center gap-2.5 text-lg/2 hover:cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket text-gray-600 group-hover:text-red-500"></i>
                        <a href="/logout" class="text-gray-600 group-hover:text-red-500">Cerrar sesión</a>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-auto p-8">
                <?php echo $content; ?>
            </main>
        </div>
    </div>
</body>

</html>