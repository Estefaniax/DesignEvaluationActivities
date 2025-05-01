<?php // accessibility_header.php ?>
<div class="accessibility-header">
  <h2>Opciones de Accesibilidad</h2>
  <div class="accessibility-controls">
    <button id="dark-mode-toggle">🌙</button>
    <button id="high-contrast-toggle">⚫</button>
    <button id="text-size-toggle">A A</button>
    <button id="accessible-style-toggle">🎨 Diseño Accesible</button>
    <button id="reset-accessibility">♻️</button>
  </div>
</div>

<!-- Módulo de selección de paletas -->
<div id="palette-modal" class="modal">
  <div class="modal-content">
    <span id="modal-close" class="modal-close">&times;</span>
    <h3>Selecciona paleta de colores</h3>
    <div class="palette-options">
      <button class="palette-option" data-palette="normal">Visión Normal</button>
      <button class="palette-option" data-palette="protanopia">Protanopía</button>
      <button class="palette-option" data-palette="deuteranopia">Deuteranopía</button>
      <button class="palette-option" data-palette="tritanopia">Tritanopía</button>
    </div>
  </div>
</div>
