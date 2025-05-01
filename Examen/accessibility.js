// accessibility.js
document.addEventListener('DOMContentLoaded', () => {
  const body      = document.body;
  const mainLink  = document.getElementById('main-css');
  const altLink   = document.getElementById('alt-css');

  const btnDark   = document.getElementById('dark-mode-toggle');
  const btnHighC  = document.getElementById('high-contrast-toggle');
  const btnTextSz = document.getElementById('text-size-toggle');
  const btnDesign = document.getElementById('accessible-style-toggle');
  const btnReset  = document.getElementById('reset-accessibility');

  const modal     = document.getElementById('palette-modal');
  const closeBtn  = document.getElementById('modal-close');
  const palBtns   = document.querySelectorAll('.palette-option');

  let currentTextSize = 1;

  // 1) RESTAURAR preferencia de hoja accesible
  const useAltCss = localStorage.getItem('accessible-style') === 'true';
  if (useAltCss) {
    altLink.disabled  = false;
    mainLink.disabled = true;
  } else {
    altLink.disabled  = true;
    mainLink.disabled = false;
  }

  // 2) MODO OSCURO
  if (localStorage.getItem('dark-mode') === 'true') {
    body.classList.add('dark-mode');
  }
  btnDark?.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    localStorage.setItem('dark-mode', body.classList.contains('dark-mode'));
    // si se activa modo oscuro, quita alto contraste
    if (body.classList.contains('dark-mode')) {
      body.classList.remove('high-contrast');
      localStorage.removeItem('high-contrast');
    }
  });

  // 3) ALTO CONTRASTE 
  if (localStorage.getItem('high-contrast') === 'true') {
    body.classList.add('high-contrast');
  }
  btnHighC?.addEventListener('click', () => {
    body.classList.toggle('high-contrast');
    localStorage.setItem('high-contrast', body.classList.contains('high-contrast'));
    // si activa contraste, quita modo oscuro
    if (body.classList.contains('high-contrast')) {
      body.classList.remove('dark-mode');
      localStorage.removeItem('dark-mode');
    }
  });

  // 4) TAMAÑO DE TEXTO
  const savedSize = parseInt(localStorage.getItem('text-size'), 10);
  if (savedSize === 2) {
    body.classList.add('text-large');
    currentTextSize = 2;
  } else if (savedSize === 3) {
    body.classList.add('text-xlarge');
    currentTextSize = 3;
  }
  btnTextSz?.addEventListener('click', () => {
    body.classList.remove('text-large','text-xlarge');
    switch (currentTextSize) {
      case 1: body.classList.add('text-large');  currentTextSize = 2; break;
      case 2: body.classList.add('text-xlarge'); currentTextSize = 3; break;
      case 3: currentTextSize = 1;               break;
    }
    localStorage.setItem('text-size', currentTextSize);
  });

  // 5) DISEÑO ACCESIBLE 
  btnDesign?.addEventListener('click', () => {
    altLink.disabled  = false;
    mainLink.disabled = true;
    localStorage.setItem('accessible-style', 'true');
    modal.classList.add('show');
  });

  // 6) CERRAR modal
  closeBtn?.addEventListener('click', () => modal.classList.remove('show'));
  window.addEventListener('click', e => {
    if (e.target === modal) modal.classList.remove('show');
  });

  // 7) SELECCIÓN DE PALETA
  palBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const pal = btn.dataset.palette; 
      body.classList.remove('protanopia','deuteranopia','tritanopia');
      if (pal) body.classList.add(pal);
      localStorage.setItem('palette', pal);
      modal.classList.remove('show');
    });
  });

  // 8) RESTABLECER TODO
  btnReset?.addEventListener('click', () => {
    // limpiar clases
    body.classList.remove(
      'dark-mode','high-contrast','text-large','text-xlarge',
      'protanopia','deuteranopia','tritanopia'
    );
    currentTextSize = 1;
    // restaurar hojas
    altLink.disabled  = true;
    mainLink.disabled = false;
    // limpiar storage
    localStorage.removeItem('dark-mode');
    localStorage.removeItem('high-contrast');
    localStorage.removeItem('text-size');
    localStorage.removeItem('palette');
    localStorage.removeItem('accessible-style');
  });

  // 9) RESTAURAR PALETA GUARDADA
  const savedPal = localStorage.getItem('palette') || '';
  if (savedPal) {
    body.classList.add(savedPal);
  }
});
