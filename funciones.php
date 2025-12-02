<?php

class Database {
    private $host = "localhost";
    private $db = "tu_base";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8mb4";

    public function conectar() {
        $credenciales = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        return new PDO($credenciales, $this->user, $this->pass, $options);
    }
}

class Usuario {
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;
    public $fecha_alta;
    public $avatar;

    public function __construct($data = []) {
        $this->id         = $data['id'] ?? null;
        $this->nombre     = $data['nombre'] ?? '';
        $this->email      = $data['email'] ?? '';
        $this->password   = $data['password'] ?? '';
        $this->rol        = $data['rol'] ?? '';
        $this->fecha_alta = $data['fecha_alta'] ?? date('Y-m-d');
        $this->avatar     = $data['avatar'] ?? null;
    }
}

class UsuarioCosas {

    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->conectar();
    }

    public function obtenerTodos() {
        return $this->db->query("SELECT * FROM usuarios ORDER BY id")->fetchAll();
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function login($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function crear(Usuario $u) {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, fecha_alta, avatar)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $u->nombre,
            $u->email,
            $u->password,
            $u->rol,
            $u->fecha_alta,
            $u->avatar
        ]);
    }

    public function actualizar(Usuario $u) {
        $sql = "UPDATE usuarios 
                SET nombre=?, email=?, password=?, rol=?, fecha_alta=?, avatar=? 
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $u->nombre,
            $u->email,
            $u->password,
            $u->rol,
            $u->fecha_alta,
            $u->avatar,
            $u->id
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
