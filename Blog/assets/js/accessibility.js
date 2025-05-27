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

  applyMode(localStorage.getItem('mode') || 'none');
  updateTextSize();
  applyFont(localStorage.getItem('font') || 'default');
  applyCursor(localStorage.getItem('cursor') || 'default');
  applyImages(localStorage.getItem('images') || 'show');
  applyLinks(localStorage.getItem('links') || 'show');
  applyVisionMode(localStorage.getItem('visionMode') || 'normal');

  // Sidebar
  accIcon.addEventListener('mouseenter', () => accSidebar.classList.add('open'));
  accIcon.addEventListener('click', () => accSidebar.classList.add('open'));
  accCloseBtn.addEventListener('click', () => accSidebar.classList.remove('open'));
  accSidebar.addEventListener('mouseleave', () => accSidebar.classList.remove('open'));

  // Botones
  darkBtn.onclick = () => toggleClass('dark-mode', 'mode', 'dark');
  contrastBtn.onclick = () => toggleClass('high-contrast', 'mode', 'contrast');
  enlargeBtn.onclick = () => {
    if (textSizeLevel < 3) textSizeLevel++;
    updateTextSize();
  };
  reduceBtn.onclick = () => {
    if (textSizeLevel > -3) textSizeLevel--;
    updateTextSize();
  };
  fontBtn.onclick = () => toggleHTMLClass('font-alt', 'font', 'alt');
  cursorBtn.onclick = () => toggleClass('cursor-large', 'cursor', 'large');
  imagesBtn.onclick = () => toggleClass('no-images', 'images', 'hide');
  linksBtn.onclick = () => toggleClass('no-links', 'links', 'hide');

  resetBtn.onclick = () => {
    localStorage.clear();
    location.reload();
  };

  simuBtn.onclick = () => visionBox.classList.toggle('open');
  visionBox.querySelectorAll('button').forEach(btn => {
    btn.onclick = () => {
      localStorage.setItem('visionMode', btn.dataset.mode);
      applyVisionMode(btn.dataset.mode);
      visionBox.classList.remove('open');
    };
  });

  // Funciones auxiliares
  function toggleClass(cls, key, val) {
    const state = body.classList.toggle(cls) ? val : 'none';
    localStorage.setItem(key, state);
  }

  function toggleHTMLClass(cls, key, val) {
    const state = document.documentElement.classList.toggle(cls) ? val : 'default';
    localStorage.setItem(key, state);
  }

  function applyMode(mode) {
    body.classList.remove('dark-mode', 'high-contrast');
    if (mode === 'dark') body.classList.add('dark-mode');
    else if (mode === 'contrast') body.classList.add('high-contrast');
  }

  function updateTextSize() {
    document.documentElement.classList.remove(
      'enlarge-1', 'enlarge-2', 'enlarge-3',
      'reduce-1', 'reduce-2', 'reduce-3'
    );
    const abs = Math.abs(textSizeLevel);
    if (textSizeLevel > 0) {
      document.documentElement.classList.add(`enlarge-${abs}`);
    } else if (textSizeLevel < 0) {
      document.documentElement.classList.add(`reduce-${abs}`);
    }
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
    body.classList.remove('protanopia', 'deuteranopia', 'tritanopia');
    if (mode !== 'normal') body.classList.add(mode);
  }
});
