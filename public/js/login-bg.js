// Inventory login background – floating boxes & barcode strips
document.addEventListener('DOMContentLoaded', () => {
  const bg = document.getElementById('login-bg');
  if (!bg) return;

  // Outline package box icon
  const boxSVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linejoin="round">
      <path d="M12 2 3 7v10l9 5 9-5V7z"/><path d="M3 7l9 5 9-5M12 12v10"/><path d="M7.5 4.5l9 5"/>
    </svg>`;

  // Floating boxes (fewer on phones)
  const BOX_COUNT = window.innerWidth < 600 ? 8 : 16;
  for (let i = 0; i < BOX_COUNT; i++) {
    const b = document.createElement('div');
    b.className = 'box';
    const size = 26 + Math.random() * 46;                       // 26–72px
    b.style.width = b.style.height = size + 'px';
    b.style.left = Math.random() * 100 + '%';
    b.style.animationDuration = 22 + Math.random() * 26 + 's';  // slow
    b.style.animationDelay = -Math.random() * 40 + 's';         // start mid-way
    b.style.setProperty('--o', (0.12 + Math.random() * 0.18).toFixed(2));
    b.style.setProperty('--r', (Math.random() * 60 - 30).toFixed(0) + 'deg');
    b.innerHTML = boxSVG;
    bg.appendChild(b);
  }

  // Barcode strips
  const spots = [[8, 18], [78, 12], [12, 72], [82, 70], [45, 88]];
  spots.forEach(([x, y], i) => {
    const bc = document.createElement('div');
    bc.className = 'barcode';
    bc.style.left = x + '%';
    bc.style.top = y + '%';
    bc.style.animationDelay = (i * 2.2) + 's';
    for (let j = 0; j < 22; j++) {
      const bar = document.createElement('i');
      bar.style.width = [1, 1, 2, 3][Math.floor(Math.random() * 4)] + 'px';
      bc.appendChild(bar);
    }
    bg.appendChild(bc);
  });
});
