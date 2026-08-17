<?php
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // --- NUEVA VALIDACIÓN: Verificar que aceptó los términos ---
    if (!isset($_POST['acepto_terminos']) || !isset($_POST['acepto_privacidad'])) {
        $error = "Debes aceptar los Términos y el Aviso de Privacidad.";
    } else {
        /* Verificar si usuario existe */
        $check = $conn->query("SELECT * FROM usuarios WHERE username='$username'");

        if ($check->num_rows > 0) {
            $error = "El nombre de usuario ya está en uso.";
        } else {
            /* Registrar usuario */
            if ($conn->query("INSERT INTO usuarios (username, password) VALUES ('$username', '$password')")) {

                /* Iniciar sesión automáticamente */
                $_SESSION['user'] = $username;
                $_SESSION['session_id'] = session_id();

                /* REDIRECCIÓN INTELIGENTE */
                if(isset($_SESSION['redirect_after_login'])){
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                } else {
                    header("Location: galeria.php");
                }
                exit();

            } else {
                $error = "Error al registrar.";
            }
        }
    }
}
?>

<div class="form-container">

<h2>Crear Cuenta</h2>

<?php
if(isset($error)){
    // Usamos la clase error-msg que pusimos en el CSS para que se vea bonito
    echo "<p class='error-msg'>$error</p>";
}
?>

<form method="POST" action="">

    <label>Elige un Usuario:</label>
    <input type="text" name="username" required>

    <label>Crea una Contraseña:</label>
    <input type="password" name="password" required>

    <div class="terminos-container">
        
        <div>
            <input type="checkbox" id="acepto_terminos" name="acepto_terminos" required>
            <label for="acepto_terminos">
                He leído y acepto los <a href="terminos.php" target="_blank">Términos y Condiciones</a> de BlueStore.
            </label>
        </div>

        <div>
            <input type="checkbox" id="acepto_privacidad" name="acepto_privacidad" required>
            <label for="acepto_privacidad">
                Acepto el <a href="terminos.php" target="_blank">Aviso de Privacidad</a> para el manejo de mis datos.
            </label>
        </div>

    </div>

    <button type="submit" class="btn">Registrarme</button>

</form>

<p style="text-align:center; margin-top:15px;">
    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
</p>

</div>

<?php include 'includes/footer.php'; ?>