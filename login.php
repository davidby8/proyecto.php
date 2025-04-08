<?php
session_start();

$host = "localhost"; 
$dbname = "Muebles";
$username = "danielgil"; 
$password = "12345678"; 

// Conexión a la base de datos (para login.php)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM usuaris WHERE username = ? LIMIT 1");
        $stmt->execute([$_POST['usuario']]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($_POST['contrasena'], $usuario['contrasenya'])) {
            $_SESSION['user'] = $usuario;
            header("Location: dashboard.php");
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
    <title><?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'Iniciar Sesión' : 'Muebles'; ?></title>
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

        /* Hero Section (para index.php) */
        .hero {
            background-color: #f8f9fa;
            padding: 80px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            color: var(--color-primario);
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }

        .btn-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-primary {
            background-color: var(--color-secundario);
            color: var(--texto-claro);
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2ecc71;
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--color-primario);
            border: 2px solid var(--color-primario);
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: var(--color-primario);
            color: var(--texto-claro);
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

        /* Sección "Sobre Nosotros" */
        .about {
            padding: 60px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .about h2 {
            color: var(--color-primario);
            text-align: center;
            margin-bottom: 30px;
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
    <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
        <!-- Contenido de la página de inicio -->
        <section class="hero">
            <h1>Muebles</h1>
            <p>Donde el diseño y la comodidad se encuentran</p>
            
            <h2>Bienvenido a nuestra tienda</h2>
            <p>Explora nuestra exclusiva colección de muebles para tu hogar.</p>
            
            <div class="btn-container">
                <a href="catalogo.php" class="btn-primary">Ver Productos</a>
                <a href="login.php" class="btn-secondary">Iniciar Sesión</a>
            </div>
        </section>

        <section class="about">
            <h2>Sobre Nosotros</h2>
            <p>En <strong>Muebles</strong>, nos dedicamos a ofrecerte los mejores muebles con diseños modernos y clásicos. Nuestra misión es transformar tus espacios en lugares únicos y acogedores.</p>
        </section>

    <?php elseif(basename($_SERVER['PHP_SELF']) == 'login.php'): ?>
        <!-- Contenido de la página de login -->
        <section class="hero">
            <h1>Primavera,<br>el despertar<br>de las ofertas</h1>
            <a href="catalogo.php" class="btn-primary">VER CATÁLOGO</a>
        </section>

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
    <?php endif; ?>
</main>

<footer>
    © <?php echo date('Y'); ?> Muebles. Todos los derechos reservados.
</footer>

</body>
</html>