<?php
// contacto.php
include 'functions.php';   

$hcaptcha_sitekey = 'f5e47742-e058-4236-adae-aa2cb3c6d598';
$hcaptcha_secret  = 'ES_6b2270267ad34009be1d1c3d3478abb5';
// -------------------------------------------------------

$errors  = [];
$name    = '';
$email   = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge datos
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $token   = $_POST['h-captcha-response'] ?? '';

    // Verifica hCaptcha
    $verify = file_get_contents(
        "https://hcaptcha.com/siteverify?secret={$hcaptcha_secret}&response={$token}"
    );
    $resp = json_decode($verify, true);

    if (empty($resp['success']) || $resp['success'] !== true) {
        $errors[] = 'Captcha inválido, por favor inténtalo de nuevo.';
    } else {
        // Si el captcha es válido, reenviar a FormSubmit
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head><meta charset="UTF-8"><title>Redirigiendo…</title></head>
        <body>
            <form id="redirectForm"
                  action="https://formsubmit.co/csamano@ucol.mx"
                  method="POST">
                <input type="hidden" name="_autoresponse"
                       value="Gracias por contactarme. Te responderé pronto.">
                <input type="hidden" name="_subject"
                       value="Nuevo mensaje desde formulario de contacto">
                <input type="hidden" name="name"
                       value="<?= htmlspecialchars($name, ENT_QUOTES) ?>">
                <input type="hidden" name="email"
                       value="<?= htmlspecialchars($email, ENT_QUOTES) ?>">
                <input type="hidden" name="message"
                       value="<?= htmlspecialchars($message, ENT_QUOTES) ?>">
            </form>
            <script>document.getElementById('redirectForm').submit();</script>
        </body>
        </html>
        <?php
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto – Mi Blog</title>

  <!-- Tipografía y estilos globales -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">

  <!-- hCaptcha -->
  <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>
<body>

  <?php include 'header.php'; ?>

  <main class="container" role="main">
    <h1 tabindex="0">Contacto</h1>
    <p>¿Tienes alguna duda o sugerencia? Escríbeme y te responderé lo antes posible.</p>

    <?php if (!empty($errors)): ?>
      <div role="alert" class="form-error">
        <?php foreach ($errors as $err): ?>
          <p><?= htmlspecialchars($err, ENT_QUOTES) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" aria-label="Formulario de contacto">

      <!-- AVISO VISIBLE DE ENVÍO SIN CIFRADO -->
      <div class="form-notice" role="alert">
        Aviso: la información enviada a través de este formulario se transmitirá por correo electrónico <strong>sin cifrado</strong>, por lo que no podemos garantizar su confidencialidad.
      </div>

      <input type="text" name="_honey" style="display:none">

      <label for="name">Nombre:</label>
      <input type="text" id="name" name="name" required
             value="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
             placeholder="Tu nombre">

      <label for="email">Correo Electrónico:</label>
      <input type="email" id="email" name="email" required
             value="<?= htmlspecialchars($email, ENT_QUOTES) ?>"
             placeholder="tu@correo.com">

      <label for="message">Mensaje:</label>
      <textarea id="message" name="message" rows="6" required
                placeholder="Escribe tu mensaje aquí"><?= htmlspecialchars($message) ?></textarea>

      <!-- Widget de hCaptcha -->
      <div class="h-captcha" data-sitekey="<?= htmlspecialchars($hcaptcha_sitekey) ?>"></div>

      <button type="submit" class="btn-primary">Enviar</button>
    </form>
  </main>

  <footer>
    &copy; <?= date('Y') ?> Mini Blog. ODS 12 Garantizar modalidades de consumo y producción sostenibles.
  </footer>

  <script src="assets/js/accessibility.js" defer></script>
</body>
</html>
