document.addEventListener('DOMContentLoaded', () => {
  const darkBtn     = document.getElementById('toggle-dark');
  const contrastBtn = document.getElementById('toggle-contrast');
  const enlargeBtn  = document.getElementById('text-toggle');
  const resetBtn    = document.getElementById('reset-accessibility');
  const simuBtn     = document.getElementById('simulate-vision-btn');
  const visionBox   = document.getElementById('vision-modes');
  let level = 0;

  // Carga estado
  const savedMode   = localStorage.getItem('mode')       || 'none';
  const savedText   = parseInt(localStorage.getItem('textLevel') || '0', 10);
  const savedVision = localStorage.getItem('visionMode') || 'normal';

  applyMode(savedMode);
  level = savedText;
  applyTextLevel(level);
  applyVisionMode(savedVision);

  // Eventos
  darkBtn.addEventListener('click', () => {
    const newMode = document.body.classList.contains('dark-mode') ? 'none' : 'dark';
    applyMode(newMode);
    localStorage.setItem('mode', newMode);
  });
  contrastBtn.addEventListener('click', () => {
    const newMode = document.body.classList.contains('high-contrast') ? 'none' : 'contrast';
    applyMode(newMode);
    localStorage.setItem('mode', newMode);
  });
  enlargeBtn.addEventListener('click', () => {
    level = (level + 1) % 4;
    localStorage.setItem('textLevel', level.toString());
    applyTextLevel(level);
  });
  resetBtn.addEventListener('click', () => {
    localStorage.setItem('mode', 'none');
    localStorage.setItem('textLevel', '0');
    localStorage.setItem('visionMode', 'normal');
    level = 0;
    applyMode('none');
    applyTextLevel(0);
    applyVisionMode('normal');
    visionBox.style.display = 'none';
  });
  simuBtn.addEventListener('click', () => {
    visionBox.style.display = visionBox.style.display === 'flex' ? 'none' : 'flex';
  });
  visionBox.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      const vm = btn.dataset.mode;
      localStorage.setItem('visionMode', vm);
      applyVisionMode(vm);
      visionBox.style.display = 'none';
    });
  });

  // Funciones
  function applyMode(mode) {
    document.body.classList.remove('dark-mode','high-contrast');
    if (mode === 'dark')     document.body.classList.add('dark-mode');
    if (mode === 'contrast') document.body.classList.add('high-contrast');
  }
  function applyTextLevel(lv) {
    document.documentElement.classList.remove('enlarge-1','enlarge-2','enlarge-3');
    if (lv > 0) document.documentElement.classList.add(`enlarge-${lv}`);
  }
  function applyVisionMode(vm) {
    document.body.classList.remove('protanopia','deuteranopia','tritanopia');
    if (vm !== 'normal') document.body.classList.add(vm);
  }
});