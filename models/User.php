<?php

namespace Models;

class User extends Database {
    protected static $table = 'users';
    protected static $columns = ['id', 'name', 'last_name', 'username', 'password', 'role'];

    public $id;
    public $name;
    public $last_name;
    public $username;
    public $password;
    public $role;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->last_name = $args['last_name'] ?? '';
        $this->username = $args['username'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->role = $args['role'] ?? '0';
    }

    public function validateAccount() {
        if (!$this->name) {
            self::$alerts['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->last_name) {
            self::$alerts['error'][] = 'El apellido es obligatorio';
        }
        if (!$this->username) {
            self::$alerts['error'][] = 'El nombre de usuario es obligatorio';
        }
        if (!$this->password) {
            self::$alerts['error'][] = 'La contraseña es obligatoria';
        }
        if (strlen($this->password) < 8) {
            self::$alerts['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        return self::$alerts;
    }

    public function validateLogin() {
        if (!$this->username) {
            self::$alerts['error'][] = 'El nombre de usuario es obligatorio';
        }
        if (!$this->password) {
            self::$alerts['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alerts;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password) {
        $result = password_verify($password, $this->password);

        if (!$result) {
            self::$alerts['error'][] = 'La contraseña es incorrecta';
        } else {
            return true;
        }
    }
}
