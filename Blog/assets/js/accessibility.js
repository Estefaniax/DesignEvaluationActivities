document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const accIcon = document.getElementById('accessibility-toggle');
  const accSidebar = document.getElementById('accessibility-sidebar');
  const accCloseBtn = document.getElementById('close-accessibility');
  const darkBtn = document.getElementById('toggle-dark');
  const contrastBtn = document.getElementById('toggle-contrast');
  const enlargeBtn = document.getElementById('text-toggle');
  const reduceBtn = document.getElementById('reduce-text');
  const fontBtn = document.getElementById('toggle-font');
  const cursorBtn = document.getElementById('toggle-cursor');
  const imagesBtn = document.getElementById('toggle-images');
  const linksBtn = document.getElementById('toggle-links');
  const resetBtn = document.getElementById('reset-accessibility');
  const simuBtn = document.getElementById('simulate-vision-btn');
  const visionBox = document.getElementById('vision-modes');

  let textSizeLevel = parseInt(localStorage.getItem('textSizeLevel') || '0', 10);

  // Inicialización de modos y ajustes previos
  applyMode(localStorage.getItem('mode') || 'none');
  updateTextSize();
  applyFont(localStorage.getItem('font') || 'default');
  applyCursor(localStorage.getItem('cursor') || 'default');
  applyImages(localStorage.getItem('images') || 'show');
  applyLinks(localStorage.getItem('links') || 'show');
  applyVisionMode(localStorage.getItem('visionMode') || 'normal');

  // Sidebar: abrir y cerrar
  accIcon.addEventListener('mouseenter', () => accSidebar.classList.add('open'));
  accIcon.addEventListener('click', () => accSidebar.classList.add('open'));
  accCloseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    accSidebar.classList.remove('open');
  });
  accSidebar.addEventListener('mouseleave', () => accSidebar.classList.remove('open'));

  // Botones de modo: cambiar directamente, sin requerir desactivar primero
  darkBtn.onclick = (e) => {
    e.stopPropagation();
    localStorage.setItem('mode', 'dark');
    applyMode('dark');
  };
  contrastBtn.onclick = (e) => {
    e.stopPropagation();
    localStorage.setItem('mode', 'contrast');
    applyMode('contrast');
  };

  // Ajustes de tamaño de texto
  enlargeBtn.onclick = (e) => { e.stopPropagation(); if (textSizeLevel < 3) textSizeLevel++; updateTextSize(); };
  reduceBtn.onclick = (e) => { e.stopPropagation(); if (textSizeLevel > -3) textSizeLevel--; updateTextSize(); };

  // Otros toggles de accesibilidad
  fontBtn.onclick = (e) => { e.stopPropagation(); toggleHTMLClass('font-alt', 'font', 'alt'); };
  cursorBtn.onclick = (e) => { e.stopPropagation(); toggleClass('cursor-large', 'cursor', 'large'); };
  imagesBtn.onclick = (e) => { e.stopPropagation(); toggleClass('no-images', 'images', 'hide'); };
  linksBtn.onclick = (e) => { e.stopPropagation(); toggleClass('no-links', 'links', 'hide'); };

  // Resetear ajustes sin cerrar el sidebar
  resetBtn.onclick = (e) => {
    e.stopPropagation();
    localStorage.clear();
    applyMode('none');
    updateTextSize();
    applyFont('default');
    applyCursor('default');
    applyImages('show');
    applyLinks('show');
    applyVisionMode('normal');
  };

  // Dropdown de simulación de visión
  simuBtn.onclick = (e) => { e.stopPropagation(); visionBox.classList.toggle('open'); };
  visionBox.querySelectorAll('button').forEach(btn => {
    btn.onclick = (e) => {
      e.stopPropagation();
      localStorage.setItem('visionMode', btn.dataset.mode);
      applyVisionMode(btn.dataset.mode);
      // No cerramos el dropdown para permitir múltiples selecciones
    };
  });

  // Funciones auxiliares
  function toggleClass(cls, key, val) {
    const active = body.classList.toggle(cls);
    const state = active ? val : 'none';
    localStorage.setItem(key, state);
  }

  function toggleHTMLClass(cls, key, val) {
    const active = document.documentElement.classList.toggle(cls);
    const state = active ? val : 'default';
    localStorage.setItem(key, state);
  }

  function applyMode(mode) {
    // Elimina ambos modos antes de aplicar el nuevo
    body.classList.remove('dark-mode', 'high-contrast');
    if (mode === 'dark') body.classList.add('dark-mode');
    else if (mode === 'contrast') body.classList.add('high-contrast');
  }

  function updateTextSize() {
    const classes = ['enlarge-1','enlarge-2','enlarge-3','reduce-1','reduce-2','reduce-3'];
    document.documentElement.classList.remove(...classes);
    if (textSizeLevel > 0) document.documentElement.classList.add(`enlarge-${Math.abs(textSizeLevel)}`);
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
