<?php
// header.php
$user = $_SESSION['user'] ?? null;
?>
<header>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/open-dyslexic-fonts@1.0.0/open-dyslexic.min.css">

  <div class="header-top">
    <button id="accessibility-toggle" aria-label="Abrir menú de accesibilidad">

<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15 15"><path fill="#00000" fill-rule="evenodd" d="M.877 7.5a6.623 6.623 0 1 1 13.246 0a6.623 6.623 0 0 1-13.246 0M7.5 1.827a5.673 5.673 0 1 0 0 11.346a5.673 5.673 0 0 0 0-11.346M7.125 9c-.055.127-.793 2.96-.793 2.96a.5.5 0 1 1-.966-.26s.88-2.827.88-3.43V6.801l-1.958-.525a.5.5 0 1 1 .258-.966s1.654.563 2.3.563h1.309c.645 0 2.298-.563 2.298-.563a.5.5 0 1 1 .26.966l-1.966.527V8.27c0 .603.88 3.427.88 3.427a.5.5 0 0 1-.966.259S7.92 9.127 7.869 9c-.05-.127-.219-.127-.219-.127h-.307s-.173 0-.218.127M7.5 5.12a1.125 1.125 0 1 0 0-2.25a1.125 1.125 0 0 0 0 2.25" clip-rule="evenodd"/></svg>
    </button>
    <div class="logo">ECOBLOG ODS 12</div>
  </div>

  <!-- Navegación principal debajo -->
  <nav class="header-nav">
    <a href="cv.php">CV</a>
    <a href="index.php">Inicio</a>
    <?php if ($user): ?>
      <a href="main.php">Panel</a>
      <a href="login.php?logout=1">Salir (<?= htmlspecialchars($user) ?>)</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
    <a href="contacto.php" class="btn-contacto">Contacto</a>
  </nav>

  <!-- Sidebar de accesibilidad -->
  <aside id="accessibility-sidebar" aria-hidden="true">
    <button id="close-accessibility" aria-label="Cerrar menú">×</button>
    <div id="accessibility-bar">
      <button id="toggle-dark">Modo oscuro</button>
      <button id="toggle-contrast">Modo contraste</button>
      <button id="text-toggle">Aumentar texto</button>
      <button id="reduce-text">Reducir texto</button>
      <button id="toggle-font">Cambiar fuente</button>
      <button id="toggle-cursor">Cambiar cursor</button>
      <button id="toggle-images">Mostrar/Ocultar imágenes</button>
      <button id="toggle-links">Mostrar/Ocultar links</button>
      <button id="reset-accessibility">Resetear accesibilidad</button>
      <button id="simulate-vision-btn" aria-expanded="false">
        Daltonismo <span class="arrow"></span>
      </button>
      <div id="vision-modes">
        <button data-mode="normal">Visión normal</button>
        <button data-mode="protanopia">Protanopia</button>
        <button data-mode="deuteranopia">Deuteranopia</button>
        <button data-mode="tritanopia">Tritanopia</button>
      </div>
    </div>
  </aside>
</header>
