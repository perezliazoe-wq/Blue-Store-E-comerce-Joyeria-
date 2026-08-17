<?php
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM usuarios WHERE username='$username' AND password='$password'");

    if ($result->num_rows > 0) {

        $_SESSION['user'] = $username;
        $_SESSION['session_id'] = session_id();

        if(isset($_SESSION['redirect_after_login'])){
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
        }else{
            header("Location: galeria.php");
        }
        exit();

    } else {

        $error = "Credenciales incorrectas.";

    }
}
?>

<div class="form-container">

    <h2>Iniciar Sesión</h2>

    <?php
    if(isset($_GET['msg']) && $_GET['msg']=="login_required"){
        echo "<p class='msg-login'>Debes iniciar sesión antes de pagar.</p>";
    }

    if(isset($error)){
        echo "<p style='color:red'>$error</p>";
    }
    ?>

    <form method="POST" action="">

        <label>Usuario:</label>
        <input type="text" name="username" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Entrar</button>

    </form>

    <p style="margin-top:15px">
    ¿No tienes cuenta?
    <a href="registro.php">Regístrate aquí</a>
    </p>

</div>

<?php include 'includes/footer.php'; ?>