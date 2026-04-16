<h1 class="mb-6 inline-flex w-full items-center justify-center gap-2 text-center text-3xl font-bold tracking-tight text-slate-800"><i class="fa-solid fa-user-plus text-blue-600"></i>Crear una cuenta nueva</h1>

<?php include_once __DIR__ . '/../templates/alerts.php'; ?>

<form action="/create-account" method="POST" class="space-y-4">
    <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-slate-700">Nombre</label>
        <input type="text" name="name" id="name" data-format="titlecase" placeholder="Tu nombre" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" value="<?php echo $user->name; ?>">
    </div>

    <div class="space-y-2">
        <label for="last_name" class="block text-sm font-medium text-slate-700">Apellido</label>
        <input type="text" name="last_name" id="last_name" data-format="titlecase" placeholder="Tu apellido" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" value="<?php echo $user->last_name; ?>">
    </div>

    <div class="space-y-2">
        <label for="username" class="block text-sm font-medium text-slate-700">Usuario</label>
        <input type="username" name="username" id="username" placeholder="Tu usuario" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" value="<?php echo $user->username; ?>">
    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-medium text-slate-700">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>

    <div>
        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-semibold text-white transition hover:bg-blue-700"><i class="fa-solid fa-user-check"></i>Crear cuenta</button>
    </div>
</form>