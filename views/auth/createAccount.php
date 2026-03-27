<h1>Crear una cuenta nueva</h1>

<?php include_once __DIR__ . '/../templates/alerts.php'; ?>

<form action="/create-account" method="POST">
    <div class="mb-4">
        <label for="name" class="block text-gray-700 font-bold mb-2">Nombre</label>
        <input type="text" name="name" id="name" placeholder="Tu nombre" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" value="<?php echo $user->name; ?>">
    </div>

    <div class="mb-4">
        <label for="last_name" class="block text-gray-700 font-bold mb-2">Apellido</label>
        <input type="text" name="last_name" id="last_name" placeholder="Tu apellido" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" value="<?php echo $user->last_name; ?>">
    </div>

    <div class="mb-4">
        <label for="username" class="block text-gray-700 font-bold mb-2">Usuario</label>
        <input type="username" name="username" id="username" placeholder="Tu usuario" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" value="<?php echo $user->username; ?>">
    </div>

    <div class="mb-4">
        <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Crear cuenta</button>
    </div>
</form>