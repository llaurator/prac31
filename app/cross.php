<?php
session_start();

$commentErr = $comment = $commentErr2 = $comment2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (array_key_exists('submit1', $_POST)) {
    if (empty($_POST['comment'])) {
      $commentErr = "* Comment is required";
    } else {
      $comment = $_POST['comment'];
    }
  }

  if (array_key_exists('submit2', $_POST)) {
    if (empty($_POST['comment2'])) {
      $commentErr2 = "* Comment is required";
    } else {
      $comment2 = trim(strip_tags($_POST['comment2']));
    }
  }
}
?>



<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cross Site Scripting</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <div class="container">
    <header class="text-center">
      <h1 class="display-1 m-5">Cross Site Scripting</h1>
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
          <div class="mb-3">
            <label for="comment" class="form-label">Comentario</label>
            <input type="text" name="comment" class="form-control" id="comment" value="<?= $comment; ?>"> <span>
              <?= $commentErr; ?>
            </span>
          </div>
          <button type="submit" class="btn btn-secondary" name="submit1">Submit</button>
          <form>
            <h2 class="mt-4">
              <?php echo $comment ?>
            </h2>
      </section>

      <section class="p-4 m-4 col border rounded">
        <h3 class="my-3">Protegido</h3>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <div class="mb-3">
            <label for="comment2" class="form-label">Comentario</label>
            <input type="text" name="comment2" class="form-control" id="comment" value="<?= $comment2; ?>"> <span>
              <?= $commentErr2; ?>
            </span>
          </div>
          <button type="submit" class="btn btn-secondary" name="submit2">Submit</button>
          <form>
            <h2 class="mt-4">
              <?php echo $comment2 ?>
            </h2>
      </section>

    </article>
    <article>
      <section class="p-4 my-4 col border rounded">
        <p>Podemos comprobar la vulnerabilidad pasando como comentario el codigo
          <code>&lt;script&gt;alert("hola")&lt;/script&gt;</code> en el primer formulario, tambien comprobamos que si lo
          pasamos en el segundo formulario el codigo no tendra efecto, la diferencia está en la forma de recoger los
          datos en cada uno ellos.
        </p>
        <p>En el primer formulario se recogen los datos sin tratar <code>$comment = $_POST['comment'];</code>, en el
          segundo se recogen los datos introducidos usando la funcion <a
            href="https://www.php.net/manual/en/function.strip-tags.php">Strip_tags()</a> que permite eliminar todas las
          etiquetas HTML y PHP de una cadena dada, tambien usaremos <a
            href="https://www.php.net/manual/es/function.trim.php">trim()</a> para eliminar espacio en blanco del inicio
          y el final de la cadena, al final la recogida de datos quedará de la siguiente forma
          <code>$comment2 = trim(strip_tags($_POST['comment2']));</code></p>
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
          <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-twitter"></i></a>

          <!-- Google -->
          <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-google"></i></a>

          <!-- Instagram -->
          <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-instagram"></i></a>

          <!-- Linkedin -->
          <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
              class="fab fa-linkedin-in"></i></a>

          <!-- Github -->
          <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-github"></i></a>
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