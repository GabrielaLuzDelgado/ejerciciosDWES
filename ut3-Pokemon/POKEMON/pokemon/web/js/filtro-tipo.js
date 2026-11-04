// Filtro por tipo: oculta las tarjetas del catálogo que no son del tipo elegido.
// Va envuelto en una función para que sus variables no choquen con las de app.js.
(() => {
  const filtro = document.getElementById('filtro-tipos');
  const rejilla = document.getElementById('rejilla');
  const estado = document.getElementById('filtro-estado');
  if (!filtro || !rejilla || !estado) return;
  let tipo = '';
  const aplicar = () => {
    let visibles = 0;
    rejilla.querySelectorAll('.tarjeta').forEach((t) => {
      const ok = !tipo || t.dataset.tipo === tipo;
      t.hidden = !ok;
      if (ok) visibles++;
    });
    filtro.querySelectorAll('button').forEach((b) => {
      b.setAttribute('aria-pressed', String((b.dataset.tipo || '') === tipo));
    });
    if (!tipo) estado.textContent = '';
    else if (visibles) estado.textContent = `${visibles} Pokémon de tipo ${tipo} en esta lista`;
    else estado.textContent = `Ningún Pokémon de tipo ${tipo} entre los resultados cargados`;
  };
  filtro.addEventListener('click', (e) => {
    const b = e.target.closest('button');
    if (!b) return;
    const t = b.dataset.tipo || '';
    tipo = tipo === t ? '' : t;   // pulsar el activo lo desactiva
    aplicar();
  });
  // app.js repinta las tarjetas al buscar: se reaplica el filtro automáticamente.
  new MutationObserver(aplicar).observe(rejilla, { childList: true });
})();