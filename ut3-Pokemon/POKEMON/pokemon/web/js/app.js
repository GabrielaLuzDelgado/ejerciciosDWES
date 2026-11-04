// Filtrado en vivo: consulta api.php mientras se escribe, sin recargar la página.
const form = document.getElementById('filtros');
const inputNombre = document.getElementById('q');
const selectGen = document.getElementById('gen');
const rejilla = document.getElementById('rejilla');
const estado = document.getElementById('estado');

let temporizador;
let controlador; // permite cancelar la petición anterior si el usuario sigue escribiendo

// Escapa texto antes de meterlo en innerHTML (evita XSS)
const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

const tarjeta = (p) => `
  <article class="tarjeta" data-tipo="${esc(p.tipo)}">
    <span class="tarjeta__num insignia">#${String(p.id).padStart(4, '0')}</span>
    <img class="tarjeta__imagen" src="${esc(p.imagen_url)}" alt="" loading="lazy" width="150" height="150">
    <h2 class="tarjeta__titulo">${esc(p.nombre)}</h2>
    <span class="chip" data-tipo="${esc(p.tipo)}">${esc(p.tipo)}</span>
    <p class="tarjeta__meta">${esc(p.altura_m)} m · ${esc(p.peso_kg)} kg</p>
    <p class="tarjeta__suave">${esc(p.gen_nombre)} · ${esc(p.region)}</p>
    <div class="tarjeta__acciones">
      <a class="boton boton--peq" href="ficha.php?pokemon_id=${Number(p.id)}">Capturar<span class="solo-lector"> a ${esc(p.nombre)}</span></a>
    </div>
  </article>`;

const vacio = `<div class="vacio"><p class="vacio__titulo">Ningún Pokémon coincide</p><p>Prueba con otro nombre o con «Todas las generaciones».</p></div>`;

async function buscar() {
  controlador?.abort();
  controlador = new AbortController();
  const params = new URLSearchParams({ q: inputNombre.value.trim(), gen: selectGen.value });
  rejilla.classList.add('is-cargando');
  try {
    const resp = await fetch(`api.php?${params}`, { signal: controlador.signal });
    const datos = await resp.json();
    if (!resp.ok) throw new Error(datos.error ?? resp.status);
    rejilla.innerHTML = datos.length ? datos.map(tarjeta).join('') : vacio;
    estado.textContent = datos.length ? `${datos.length} Pokémon en el catálogo` : '';
    history.replaceState(null, '', `?${params}`);
  } catch (e) {
    if (e.name !== 'AbortError') estado.textContent = e.message;
  } finally {
    rejilla.classList.remove('is-cargando');
  }
}

form.addEventListener('submit', (e) => { e.preventDefault(); buscar(); });
inputNombre.addEventListener('input', () => { clearTimeout(temporizador); temporizador = setTimeout(buscar, 250); });
selectGen.addEventListener('change', buscar);
