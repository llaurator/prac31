<?php
// Datos de conexión a la base de datos MySQL
$host = 'mariadb';
$dbname = 'probes';
$dbusername = 'root';
$dbpassword = 'Contrasenya';

$resposta = $query = $login = $login2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (array_key_exists('submit1', $_POST)) {
        try {
            // Conexión a la base de datos utilizando PDO
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            // Obtener los valores enviados desde el formulario
            $username = $_POST['username'];
            $password = $_POST['password'];

            // codigo vulnerable a SQLI - Consulta para verificar si el usuario y la contraseña son válidos
            $query = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Verificar si se encontró un registro coincidente
            if ($stmt->rowCount() > 0) {
                $login = " Inicio de sesión con exito";
            } else {
                $login = " Usuario o contraseña incorrectos";
            }
            // llenar variable con la respuesta SQL
            $resposta = "";
            foreach ($stmt->fetchAll() as $row) {
                $resposta = $resposta . $row['username'] . " " . $row['password'] . "<br>";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    if (array_key_exists('submit2', $_POST)) {
        try {
            // Conexión a la base de datos utilizando PDO
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            // Obtener los valores enviados desde el formulario
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Consulta para verificar si el usuario y la contraseña son válidos
            $query = "SELECT * FROM usuarios WHERE username = :username AND password = :password";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Verificar si se encontró un registro coincidente
            if ($stmt->rowCount() > 0) {
                $login2 = " Inicio de sesión con exito";
            } else {
                $login2 = " Usuario o contraseña incorrectos";
            }
            
            //almacenar resultados en variable
            $resposta = "";
            foreach ($stmt->fetchAll() as $row) {
                $resposta = $resposta . $row['username'] . " " . $row['password'] . "<br>";
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}
?>


<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SQL Injection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <header class="text-center">
            <h1 class="display-1 m-5">SQL Injection</h1>
        </header>
        <!-- menu -->

        <nav class="navbar navbar-expand-lg navbar-secondary bg-secondary border rounded">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="cross.php">Cross Site Scripting</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sqli.php">SQL Injection</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- fin menu -->

        <article class="row mt-4">

            <section class="p-4 m-4 col border rounded">
                <h3 class="my-3">Vulnerable</h3>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <label for="username" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="username" id="username" required><br><br>
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="password" id="password" required><br><br>
                    <button type="submit" class="btn btn-secondary me-5" name="submit1">Inicar Sesión</button>
                    <?php echo $login; ?>
                </form>
            </section>

            <section class="p-4 m-4 col border rounded">
                <h3 class="my-3">Protegido</h3>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <label for="username" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" name="username" id="username" required><br><br>
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="password" id="password" required><br><br>
                    <button type="submit" class="btn btn-secondary me-5" name="submit2">Inicar Sesión</button>
                    <?php echo $login2; ?>
                </form>
            </section>

        </article>
        <article class="row">
            <section class="p-4 m-4 col border rounded">
                <h3>Consulta SQL</h3>
                <code><?php echo $query ?></code>
            </section>
        </article>
        <article class="row">
            <section class="p-4 m-4 col border rounded">
                <h3>Respuesta SQL</h3>
                <?php echo $resposta; ?>
            </section>
        </article>

        <footer class="bg-dark text-center text-white">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <!-- Facebook -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-facebook-f"></i></a>

                    <!-- Twitter -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-twitter"></i></a>

                    <!-- Google -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-google"></i></a>

                    <!-- Instagram -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-instagram"></i></a>

                    <!-- Linkedin -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-linkedin-in"></i></a>

                    <!-- Github -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                            class="fab fa-github"></i></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © Copyright 2023 <br> Esta página está bajo licencia <a class="text-white"
                    href="https://creativecommons.org/licenses/by-nd/4.0/deed.es_ES">Creative Commons
                    Reconocimiento-SinObraDerivada 4.0 Internacional.</a><br>
                Luis, Isaac, Maxim
            </div>
            <!-- Copyright -->
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>