<?php
include 'functions.php';   // functions.php ya hace session_start()
  


$posts = getPosts();
$user  = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CV – ECOBLOG</title>

  <!-- Tipografía -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Estilos globales y accesibilidad -->
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/accessibility.css">
  <!-- Nuevo CSS para el CV -->
  <link rel="stylesheet" href="assets/css/cv.css">
</head>
<body>

  <!-- HEADER (logo, nav y accesibilidad) -->
  <?php include 'header.php'; ?>

  <!-- CONTENIDO DEL CV -->
  <main class="cv-container" role="main">

    <!-- Cabecera con foto y nombre -->
    <div class="cv-header">
      <img src="assets/images/cv2.jpg" 
           alt="Foto de Citlaly Estefanía Sámano" 
           class="profile-pic">
      <div class="info">
        <h1>Citlaly Estefanía Sámano</h1>
        <p>Ingeniero en Software</p>
      </div>
    </div>

    <!-- Datos Personales -->
    <section class="cv-section" data-title="Datos Personales">
      <ul class="cv-list">
        <li><strong>Ubicación:</strong>  Manzanillo, Colima, México</li>
        <li><strong>Email:</strong> <a href="mailto:citlaly_est@hotmail.com">citlaly_est@hotmail.com</a></li>
        <li><strong>Teléfono:</strong> <a href="tel:+523141650597">+52 314 165 0597</a></li>
        <li><strong>GitHub:</strong> <a href="https://github.com/Estefaniax" target="_blank" rel="noopener">github.com/Estefaniax</a></li>
      </ul>
    </section>

    <!-- Resumen Profesional -->
    <section class="cv-section" data-title="Resumen Profesional">
      <p>Soy Ingeniero en Software con experiencia en desarrollo web y móvil, especializado en soluciones accesibles, escalables y mantenibles. Me apasiona trabajar en equipos ágiles y mejorar la experiencia de usuario.</p>
    </section>

    <!-- Educación y Otras Habilidades (lado a lado) -->
    <div class="edu-skill">
      <section class="cv-section" data-title="Educación">
        <ul class="cv-list">
          <li><strong>Facultad de Ingeniería Electromecánica</strong> – Ingeniería en Software</li>
        </ul>
      </section>
      <section class="cv-section" data-title="Otras Habilidades">
        <ul class="cv-list">
          <li>Control de versiones (Git)</li>
          <li>Metodologías ágiles (Scrum, Kanban)</li>
          <li>Accesibilidad web (WCAG 2.1)</li>
          <li>Diseño UI/UX con Figma</li>
        </ul>
      </section>
    </div>

    <hr class="cv-divider">

    <!-- Lenguajes de Programación -->
    <section class="cv-section" data-title="Lenguajes de Programación">
      <ul class="cv-tags">
        <li>Python</li>
        <li>C++</li>
        <li>C#</li>
        <li>JavaScript</li>
        <li>PHP</li>
        <li>HTML &amp; CSS</li>
        <li>TypeScript</li>
      </ul>
    </section>

    <!-- Idiomas con gráfica de barras -->
    <section class="cv-section" data-title="Idiomas">
      <div class="language-chart">
        <div class="language-row">
          <span>Español</span>
          <div class="language-bar">
            <div class="language-bar-inner" style="width:100%"></div>
          </div>
          <div class="percent">100%</div>
        </div>
        <div class="language-row">
          <span>Inglés</span>
          <div class="language-bar">
            <div class="language-bar-inner" style="width:85%"></div>
          </div>
          <div class="percent">85%</div>
        </div>
      </div>
    </section>

    <hr class="cv-divider">

    <!-- Experiencia -->
    <section class="cv-section" data-title="Experiencia">
      <ul class="experience-list">
        <li>Desarrollo web accesible en ECOBLOG <span class="year">2025</span></li>
        <li>Metodologías ágiles (Scrum, Kanban) <span class="year">2024</span></li>
        <li>Accesibilidad web (WCAG 2.1) <span class="year">2023</span></li>
      </ul>
    </section>

  </main>


  <footer>&copy; <?= date('Y') ?> Mini Blog. Estefanía  López.</footer>
  <script src="assets/js/accessibility.js"></script>
</body>
</html>
