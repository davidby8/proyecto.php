<?php
session_start();

// Si ya está logueado, redirigir automáticamente
if (isset($_SESSION['id_usuari'])) {
    // Si el usuario ya está logueado, redirige al dashboard
    if ($_SESSION['rol'] == 'admin') { // Verifica el rol del usuario
        header("Location: admin_dashboard.php"); // Si es admin, redirige al admin_dashboard
    } else {
        header("Location: dashboard.php"); // Si no es admin, redirige al dashboard normal
    }
    exit();
}

$host = "localhost"; 
$dbname = "Muebles";
$username = "danielgil"; 
$password = "12345678"; 

// Conexión a la base de datos (para login.php)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la consulta para obtener el usuario
        $stmt = $pdo->prepare("SELECT * FROM usuaris WHERE username = ? LIMIT 1");
        $stmt->execute([$_POST['usuario']]);
        $usuario = $stmt->fetch();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($_POST['contrasena'], $usuario['contrasenya'])) {
            // Crear una sesión para el usuario
            $_SESSION['id_usuari'] = $usuario['id_usuari']; // Guarda el ID del usuario
            $_SESSION['nom_usuari'] = $usuario['nom_usuari']; // Guarda el nombre del usuario
            $_SESSION['cognom_usuari'] = $usuario['cognom_usuari']; // Guarda el apellido del usuario
            $_SESSION['rol'] = $usuario['rol']; // Guarda el rol del usuario (admin o user)

            // Redirigir según el rol del usuario
            if ($usuario['rol'] == 'admin') {
                header("Location: admin_dashboard.php"); // Redirigir al dashboard del administrador
            } else {
                header("Location: dashboard.php"); // Redirigir al dashboard de usuario normal
            }
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } catch (PDOException $e) {
        $error = "Error de conexión: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Muebles</title>
    <style>
        :root {
            --color-primario: #2c3e50;
            --color-secundario: #27ae60;
            --color-terciario: #3498db;
            --texto-claro: #ffffff;
            --texto-oscuro: #333333;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--texto-oscuro);
            line-height: 1.6;
        }

        /* Header */
        header {
            background-color: var(--color-primario);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            color: var(--texto-claro);
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Botones de autenticación */
        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-btn {
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-login {
            background-color: transparent;
            color: var(--texto-claro);
            border: 2px solid var(--texto-claro);
        }

        .btn-login:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .btn-register {
            background-color: var(--color-secundario);
            color: var(--texto-claro);
            border: 2px solid var(--color-secundario);
        }

        .btn-register:hover {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }

        /* Formulario de login (para login.php) */
        .login-section {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--texto-claro);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .login-section h2 {
            color: var(--color-primario);
            text-align: center;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--color-secundario);
            color: var(--texto-claro);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #2ecc71;
        }

        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fadbd8;
            border-radius: 4px;
        }

        footer {
            background-color: var(--color-primario);
            color: var(--texto-claro);
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo">Muebles</a>
    <div class="auth-buttons">
        <a href="login.php" class="auth-btn btn-login">Iniciar Sesión</a>
        <a href="register.php" class="auth-btn btn-register">Registrarse</a>
    </div>
</header>

<main>
    <section class="login-section">
        <h2>Iniciar Sesión</h2>
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="usuario" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="contrasena" placeholder="Contraseña" required>
            </div>
            <button type="submit" name="login" class="btn-submit">Iniciar Sesión</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2025 Muebles. Todos los derechos reservados.</p>
</footer>

</body>
</html>





