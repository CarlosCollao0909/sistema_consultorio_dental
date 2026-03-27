<div class="mb-8 text-center">
  <h1 class="text-4xl font-bold text-gray-800 mb-2">Consultorio Dental PerfectDent</h1>
  <p class="text-gray-600 text-lg">Inicia sesión con tu cuenta</p>
</div>

<?php include_once __DIR__ . '/../templates/alerts.php'; ?>

<div class="flex">
    <form action="/" method="POST">
    <div class="mb-4">
        <label for="username" class="block text-gray-700 font-bold mb-2">Usuario</label>
        <input type="username" name="username" id="username" placeholder="Tu usuario" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
    </div>

    <div class="mb-4">
        <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Iniciar sesión</button>
    </div>
</form>
</div>