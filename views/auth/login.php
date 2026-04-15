<div class="mb-8 text-center">
    <i class="fa-solid fa-tooth text-blue-600 text-3xl font-extrabold"></i>
    <h1 class="mb-2 inline-flex items-center gap-2 text-3xl font-bold tracking-tight text-slate-800">Consultorio Dental Perfect Dent</h1>
  <p class="text-slate-600">Inicia sesión con tu cuenta</p>
</div>

<?php include_once __DIR__ . '/../templates/alerts.php'; ?>

<form action="/" method="POST" class="space-y-4">
    <div class="space-y-2">
        <label for="username" class="block text-sm font-medium text-slate-700">Usuario</label>
        <input type="username" name="username" id="username" placeholder="Tu usuario" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-medium text-slate-700">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>

    <div>
        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white transition hover:bg-blue-700"><i class="fa-solid fa-right-to-bracket"></i>Iniciar sesión</button>
    </div>
</form>