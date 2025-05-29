// assets/js/accessibility.js


document.addEventListener('DOMContentLoaded', () => {
  const body        = document.body;
  const accIcon     = document.getElementById('accessibility-toggle');
  const accSidebar  = document.getElementById('accessibility-sidebar');
  const accCloseBtn = document.getElementById('close-accessibility');
  const darkBtn     = document.getElementById('toggle-dark');
  const contrastBtn = document.getElementById('toggle-contrast');
  const enlargeBtn  = document.getElementById('text-toggle');
  const reduceBtn   = document.getElementById('reduce-text');
  const fontBtn     = document.getElementById('toggle-font');
  const cursorBtn   = document.getElementById('toggle-cursor');
  const imagesBtn   = document.getElementById('toggle-images');
  const linksBtn    = document.getElementById('toggle-links');
  const resetBtn    = document.getElementById('reset-accessibility');
  const simuBtn     = document.getElementById('simulate-vision-btn');
  const visionBox   = document.getElementById('vision-modes');

  let textSizeLevel = parseInt(localStorage.getItem('textSizeLevel') || '0', 10);

  applyMode(localStorage.getItem('mode') || 'none');
  updateTextSize();
  applyFont(localStorage.getItem('font') || 'default');
  applyCursor(localStorage.getItem('cursor') || 'default');
  applyImages(localStorage.getItem('images') || 'show');
  applyLinks(localStorage.getItem('links') || 'show');
  applyVisionMode(localStorage.getItem('visionMode') || 'normal');

  //  ABRIR/CERRAR SIDEBAR DE ACCESIBILIDAD

  accIcon.addEventListener('mouseenter', () => {
    accSidebar.classList.add('open');
    accSidebar.style.removeProperty('transform');
  });

  accIcon.addEventListener('click', () => {
    accSidebar.classList.add('open');
    accSidebar.style.removeProperty('transform');
  });

  accCloseBtn.addEventListener('click', e => {
    e.stopPropagation();
    accSidebar.classList.remove('open');
    accSidebar.style.transform = 'translateX(-100%)';
  });

  accSidebar.addEventListener('mouseleave', () => {
    accSidebar.classList.remove('open');
    accSidebar.style.removeProperty('transform');
  });

  // ──────────────────────────────────────────────────────────────────
  //  TOGGLES DE ACCESIBILIDAD (Modo oscuro, contraste, texto, etc.)
  // ──────────────────────────────────────────────────────────────────

  darkBtn.onclick = e => {
    e.stopPropagation();
    const current  = localStorage.getItem('mode') || 'none';
    const nextMode = current === 'dark' ? 'none' : 'dark';
    localStorage.setItem('mode', nextMode);
    applyMode(nextMode);
  };
  // Modo alto contraste
  contrastBtn.onclick = e => {
    e.stopPropagation();
    const current  = localStorage.getItem('mode') || 'none';
    const nextMode = current === 'contrast' ? 'none' : 'contrast';
    localStorage.setItem('mode', nextMode);
    applyMode(nextMode);
  };

  // Aumentar / Reducir texto
  enlargeBtn.onclick = e => {
    e.stopPropagation();
    if (textSizeLevel < 3) textSizeLevel++;
    updateTextSize();
  };
  reduceBtn.onclick = e => {
    e.stopPropagation();
    if (textSizeLevel > -3) textSizeLevel--;
    updateTextSize();
  };

  // Cambiar fuente, cursor, mostrar/ocultar imágenes y links
  fontBtn.onclick   = e => { e.stopPropagation(); toggleHTMLClass('font-alt', 'font', 'alt'); };
  cursorBtn.onclick = e => { e.stopPropagation(); toggleClass('cursor-large', 'cursor', 'large'); };
  imagesBtn.onclick = e => { e.stopPropagation(); toggleClass('no-images', 'images', 'hide'); };
  linksBtn.onclick  = e => { e.stopPropagation(); toggleClass('no-links', 'links', 'hide'); };

  // Resetear todos los ajustes
  resetBtn.onclick = e => {
    e.stopPropagation();
    localStorage.clear();
    applyMode('none');
    textSizeLevel = 0;
    updateTextSize();
    applyFont('default');
    applyCursor('default');
    applyImages('show');
    applyLinks('show');
    applyVisionMode('normal');
  };

  // ──────────────────────────────────────────────────────────────────
  //  Simulación de visión (Daltonismo)
  // ──────────────────────────────────────────────────────────────────
  simuBtn.onclick = e => {
    e.stopPropagation();
    const isOpen = visionBox.classList.toggle('open');
    simuBtn.setAttribute('aria-expanded', isOpen);
  };
  visionBox.querySelectorAll('button').forEach(btn => {
    btn.onclick = e => {
      e.stopPropagation();
      localStorage.setItem('visionMode', btn.dataset.mode);
      applyVisionMode(btn.dataset.mode);
    };
  });

  // ──────────────────────────────────────────────────────────────────
  //  FUNCIONES AUXILIARES
  // ──────────────────────────────────────────────────────────────────
  function toggleClass(cls, key, val) {
    const active = body.classList.toggle(cls);
    localStorage.setItem(key, active ? val : 'none');
  }

  function toggleHTMLClass(cls, key, val) {
    const active = document.documentElement.classList.toggle(cls);
    localStorage.setItem(key, active ? val : 'default');
  }

  function applyMode(mode) {
    body.classList.remove('dark-mode', 'high-contrast');
    if (mode === 'dark')         body.classList.add('dark-mode');
    else if (mode === 'contrast') body.classList.add('high-contrast');
  }

  function updateTextSize() {
    const classes = ['enlarge-1','enlarge-2','enlarge-3','reduce-1','reduce-2','reduce-3'];
    document.documentElement.classList.remove(...classes);
    if (textSizeLevel > 0)      document.documentElement.classList.add(`enlarge-${textSizeLevel}`);
    else if (textSizeLevel < 0) document.documentElement.classList.add(`reduce-${Math.abs(textSizeLevel)}`);
    localStorage.setItem('textSizeLevel', textSizeLevel);
  }

  function applyFont(font) {
    document.documentElement.classList.toggle('font-alt', font === 'alt');
  }

  function applyCursor(cursor) {
    body.classList.toggle('cursor-large', cursor === 'large');
  }

  function applyImages(images) {
    body.classList.toggle('no-images', images === 'hide');
  }

  function applyLinks(links) {
    body.classList.toggle('no-links', links === 'hide');
  }

  function applyVisionMode(mode) {
    body.classList.remove('protanopia','deuteranopia','tritanopia');
    if (mode !== 'normal') body.classList.add(mode);
  }
});
