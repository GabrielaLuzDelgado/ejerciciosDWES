<?php
// Catálogo de Pokémon (solo lectura): buscador, filtro por generación y botón «Capturar».
require __DIR__ . '/repositorio.php';

$q = trim($_GET['q'] ?? '');
$gen = (int) ($_GET['gen'] ?? 0);
$avisos = [];
$generaciones = $pokemon = [];
try {
    $pdo = conectar();
    $generaciones = listarGeneraciones($pdo);
    $pokemon = buscarPokemon($pdo, $q, $gen);
} catch (Throwable $e) {
    $avisos[] = mensajeError($e);
}

abrirPagina('Catálogo', 'catalogo');
?>
<div class="titulo-pagina">
  <h1>Catálogo</h1>
  <p class="titulo-pagina__sub">Busca un Pokémon y captúralo para tu Pokédex.</p>
</div>

<?php pintarAvisos(flash(), $avisos); ?>

<form id="filtros" class="buscador" method="get" role="search">
  <label class="solo-lector" for="q">Nombre</label>
  <input class="control" id="q" name="q" type="search" placeholder="Buscar por nombre…" value="<?= h($q) ?>" autocomplete="off">
  <label class="solo-lector" for="gen">Generación</label>
  <select class="control" id="gen" name="gen">
    <option value="0">Todas las generaciones</option>
    <?php foreach ($generaciones as $g): ?>
      <option value="<?= (int) $g['id'] ?>" <?= $gen === (int) $g['id'] ? 'selected' : '' ?>><?= h($g['nombre']) ?> · <?= h($g['region']) ?></option>
    <?php endforeach; ?>
  </select>
  <noscript><button class="boton" type="submit">Buscar</button></noscript>
</form>

<div id="filtro-tipos" class="filtro-tipos" role="group" aria-label="Filtrar por tipo">
  <button class="boton boton--peq boton--secundario" type="button" data-tipo="" aria-pressed="true">Todos</button>
  <?php foreach (['normal','fire','water','electric','grass','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','dark','steel','fairy'] as $t): ?>
    <button class="chip chip--boton" type="button" data-tipo="<?= h($t) ?>" aria-pressed="false"><?= h($t) ?></button>
  <?php endforeach; ?>
</div>
<p id="filtro-estado" class="estado" aria-live="polite"></p>

<p id="estado" class="estado" aria-live="polite"><?= $pokemon ? count($pokemon) . ' Pokémon en el catálogo' : '' ?></p>
<section id="rejilla" class="rejilla" aria-label="Resultados">
  <?php foreach ($pokemon as $p): ?>
    <article class="tarjeta" data-tipo="<?= h($p['tipo']) ?>">
      <span class="tarjeta__num insignia">#<?= str_pad($p['id'], 4, '0', STR_PAD_LEFT) ?></span>
      <img class="tarjeta__imagen" src="<?= h($p['imagen_url']) ?>" alt="" loading="lazy" width="150" height="150">
      <h2 class="tarjeta__titulo"><?= h($p['nombre']) ?></h2>
      <span class="chip" data-tipo="<?= h($p['tipo']) ?>"><?= h($p['tipo']) ?></span>
      <p class="tarjeta__meta"><?= h($p['altura_m']) ?> m · <?= h($p['peso_kg']) ?> kg</p>
      <p class="tarjeta__suave"><?= h($p['gen_nombre']) ?> · <?= h($p['region']) ?></p>
      <div class="tarjeta__acciones">
        <a class="boton boton--peq" href="ficha.php?pokemon_id=<?= (int) $p['id'] ?>">Capturar<span class="solo-lector"> a <?= h($p['nombre']) ?></span></a>
      </div>
    </article>
  <?php endforeach; ?>
  <?php if (!$pokemon && !$avisos): ?>
    <div class="vacio"><p class="vacio__titulo">Ningún Pokémon coincide</p><p>Prueba con otro nombre o con «Todas las generaciones».</p></div>
  <?php endif; ?>
</section>
<?php cerrarPagina(['js/app.js', 'js/filtro-tipo.js']);