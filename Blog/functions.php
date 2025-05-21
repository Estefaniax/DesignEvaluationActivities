<?php
session_start();

define('DATA_DIR',    __DIR__ . '/data/');
define('USERS_FILE',  DATA_DIR . 'users.txt');
define('UPLOADS_DIR', __DIR__ . '/uploads/');

/**
 * Asegura que existan los directorios de datos y usuarios
 */
function ensureDataDir(): void {
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, '');
    }
}

/**
 * Asegura que exista el directorio de uploads
 */
function ensureUploadsDir(): void {
    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }
}

/* --------------------
   Gestión de usuarios
   -------------------- */

/**
 * Comprueba si un usuario ya existe
 */
function userExists(string $username): bool {
    ensureDataDir();
    $lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($u,) = explode('|', $line, 2);
        if ($u === $username) {
            return true;
        }
    }
    return false;
}

/**
 * Registra un nuevo usuario (y crea su carpeta de datos y uploads)
 */
function registerUser(string $username, string $password): void {
    ensureDataDir();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    file_put_contents(USERS_FILE, "$username|$hash\n", FILE_APPEND);

    // Carpeta de posts
    $userDir = DATA_DIR . $username . '/';
    if (!is_dir($userDir)) {
        mkdir($userDir, 0755, true);
        file_put_contents($userDir . 'posts.txt', '');
    }

    // Carpeta de uploads
    ensureUploadsDir();
    $upDir = UPLOADS_DIR . $username . '/';
    if (!is_dir($upDir)) {
        mkdir($upDir, 0755, true);
    }
}

/**
 * Verifica credenciales de usuario contra el hash
 */
function verifyUser(string $username, string $password): bool {
    ensureDataDir();
    $lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($u, $hash) = explode('|', $line, 2);
        if ($u === $username && password_verify($password, $hash)) {
            return true;
        }
    }
    // Siempre retornar false si no se encontró o contraseña falla
    return false;
}

/**
 * Autentica al usuario y prepara su carpeta de uploads
 */
function authenticate(string $username, string $password): bool {
    if (verifyUser($username, $password)) {
        $_SESSION['user'] = $username;

        // Asegurar carpeta de uploads
        ensureUploadsDir();
        $upDir = UPLOADS_DIR . $username . '/';
        if (!is_dir($upDir)) {
            mkdir($upDir, 0755, true);
        }

        return true;
    }
    return false;
}

/**
 * Redirige a login si no hay sesión activa
 */
function requireLogin(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

/* Gestión de publicaciones
  */

function getPosts(): array {
    ensureDataDir();
    $posts = [];
    foreach (scandir(DATA_DIR) as $user) {
        if (in_array($user, ['.', '..'])) continue;
        $file = DATA_DIR . $user . '/posts.txt';
        if (!file_exists($file)) continue;
        foreach (file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
            $parts = explode('|', $line);
            list($id, $title, $content, $date, $author) = $parts;
            $image = $parts[5] ?? '';
            $posts[] = compact('id','title','content','date','author','image');
        }
    }
    return $posts;
}

/**
 * Obtiene una publicación por su ID (o null si no existe)
 */
function getPost(int $id) {
    foreach (getPosts() as $p) {
        if ($p['id'] == $id) {
            return $p;
        }
    }
    return null;
}

/**
 * Crea una nueva publicación (opcionalmente con imagen subida)
 */
function savePost(string $title, string $content): void {
    ensureDataDir();
    ensureUploadsDir();

    // Nuevo ID incremental
    $maxId = 0;
    foreach (getPosts() as $p) {
        $maxId = max($maxId, (int)$p['id']);
    }
    $id     = $maxId + 1;
    $date   = date('Y-m-d H:i:s');
    $author = $_SESSION['user'];

    // Procesar imagen si se subió
    $imageName = '';
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $upDir = UPLOADS_DIR . $author . '/';
        $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = $id . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upDir . $imageName);
    }

    // Sanitizar textos
    $t = str_replace(["\r\n","\n"], [' ',' '], trim($title));
    $c = str_replace(["\r\n","\n"], [' ',' '], trim($content));

    // Guardar línea en posts.txt
    $line = implode('|', [$id, $t, $c, $date, $author, $imageName]);
    file_put_contents(DATA_DIR . $author . '/posts.txt', $line . PHP_EOL, FILE_APPEND);
}


function updatePost(int $id, string $title, string $content): bool {
    ensureDataDir();
    ensureUploadsDir();

    $author = $_SESSION['user'];
    $file   = DATA_DIR . $author . '/posts.txt';
    if (!file_exists($file)) {
        return false;
    }

    $lines   = file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    $newData = [];

    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if ((int)$parts[0] === $id && $parts[4] === $author) {
            // Imagen antigua
            $oldImage = $parts[5] ?? '';
            $newImage = $oldImage;

            // Si sube nueva imagen, reemplaza la anterior
            if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                if ($oldImage && file_exists(UPLOADS_DIR . $author . '/' . $oldImage)) {
                    unlink(UPLOADS_DIR . $author . '/' . $oldImage);
                }
                $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newImage = $id . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOADS_DIR . $author . '/' . $newImage);
            }

            $date = date('Y-m-d H:i:s');
            $t    = str_replace(["\r\n","\n"], [' ',' '], trim($title));
            $c    = str_replace(["\r\n","\n"], [' ',' '], trim($content));

            $newData[] = implode('|', [$id, $t, $c, $date, $author, $newImage]);
        } else {
            $newData[] = $line;
        }
    }

    file_put_contents($file, implode(PHP_EOL, $newData) . PHP_EOL);
    return true;
}

/**
 * Elimina una publicación (y su imagen asociada)
 */
function deletePost(int $id): bool {
    ensureDataDir();

    $author = $_SESSION['user'];
    $file   = DATA_DIR . $author . '/posts.txt';
    if (!file_exists($file)) {
        return false;
    }

    $lines   = file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    $newData = [];
    $deleted = false;

    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if ((int)$parts[0] === $id && $parts[4] === $author) {
            // Borrar la imagen del servidor
            $img = $parts[5] ?? '';
            if ($img && file_exists(UPLOADS_DIR . $author . '/' . $img)) {
                unlink(UPLOADS_DIR . $author . '/' . $img);
            }
            $deleted = true;
            continue;
        }
        $newData[] = $line;
    }

    if ($deleted) {
        file_put_contents($file, implode(PHP_EOL, $newData) . PHP_EOL);
    }

    return $deleted;
}
