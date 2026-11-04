<?php
// Mi Pokédex: los Pokémon capturados (CRUD sobre la tabla pokedex).
require __DIR__ . '/repositorio.php';

$avisos = [];
$capturas = $resumen = [];
$consultaOk = true;   // false si algo falló: así no mostramos «vacío» cuando en realidad hay un error
try {
    $pdo = conectar();
    foreach (['capturas' => fn() => listarPokedex($pdo), 'resumen' => fn() => resumenPokedex($pdo)] as $var => $cargar) {
        try {
            $$var = $cargar();
        } catch (Throwable $e) {
            $avisos[] = mensajeError($e);
            $consultaOk = false;
        }
    }
} catch (Throwable $e) {
    $avisos[] = mensajeError($e);
    $consultaOk = false;
}

abrirPagina('Mi Pokédex', 'pokedex');
?>
<div class="titulo-pagina">
  <h1>Mi Pokédex</h1>
  <p class="titulo-pagina__sub"><?= $consultaOk ? count($capturas) . ' Pokémon capturados · ' : '' ?><a href="index.php">Ir al catálogo para capturar más</a></p>
</div>

<?php pintarAvisos(flash(), $avisos); ?>

<?php if ($resumen): ?>
  <ul class="estadisticas" aria-label="Capturas por tipo">
    <?php foreach ($resumen as $r): ?>
      <li class="estadistica" data-tipo="<?= h($r['tipo']) ?>"><strong><?= (int) $r['total'] ?></strong>
        <?= h(ucfirst($r['tipo'])) ?> · nivel medio <?= h($r['nivel_medio']) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<section class="rejilla" aria-label="Capturas">
  <?php foreach ($capturas as $c): $nombreMostrado = $c['apodo'] ?: $c['nombre']; ?>
    <article class="tarjeta" data-tipo="<?= h($c['tipo']) ?>">
      <span class="tarjeta__num insignia">Nv. <?= (int) $c['nivel'] ?></span>
      <img class="tarjeta__imagen" src="<?= h($c['imagen_url']) ?>" alt="" loading="lazy" width="150" height="150">
      <h2 class="tarjeta__titulo"><?= h($nombreMostrado) ?></h2>
      <span class="chip" data-tipo="<?= h($c['tipo']) ?>"><?= h($c['tipo']) ?></span>
      <?php if ($c['apodo']): ?><p class="tarjeta__meta"><?= h($c['nombre']) ?> · #<?= str_pad($c['pokemon_id'], 4, '0', STR_PAD_LEFT) ?></p><?php endif; ?>
      <p class="tarjeta__suave">Capturado el <?= h(date('d/m/Y', strtotime($c['fecha_captura']))) ?> · <?= h($c['region']) ?></p>
      <?php if ($c['notas']): ?><p class="tarjeta__notas"><?= h($c['notas']) ?></p><?php endif; ?>
      <div class="tarjeta__acciones">
        <a class="boton boton--peq" href="ficha.php?id=<?= (int) $c['id'] ?>">Editar<span class="solo-lector"> a <?= h($nombreMostrado) ?></span></a>
        <form method="post" action="liberar.php" data-confirmar="¿Liberar a <?= h($nombreMostrado) ?>? Esta acción no se puede deshacer.">
          <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
          <input type="hidden" name="csrf" value="<?= h(csrf()) ?>">
          <button class="boton boton--peq boton--peligro" type="submit">Liberar<span class="solo-lector"> a <?= h($nombreMostrado) ?></span></button>
        </form>
      </div>
    </article>
  <?php endforeach; ?>
</section>

<?php if (!$capturas && $consultaOk): ?>
  <div class="vacio">
    <p class="vacio__titulo">Tu Pokédex está vacía</p>
    <p>Todavía no has capturado ningún Pokémon.</p>
    <a class="boton" href="index.php">Explorar el catálogo</a>
  </div>
<?php endif; ?>

<dialog class="dialogo" id="dialogo-confirmar" aria-labelledby="dialogo-titulo">
  <form method="dialog" class="dialogo__contenido">
    <h2 class="dialogo__titulo" id="dialogo-titulo">¿Liberar este Pokémon?</h2>
    <p class="dialogo__texto" id="dialogo-texto"></p>
    <div class="dialogo__acciones">
      <button class="boton boton--secundario" value="cancelar" autofocus>Cancelar</button>
      <button class="boton boton--peligro" value="confirmar">Liberar</button>
    </div>
  </form>
</dialog>
<?php cerrarPagina(['js/dialogo.js']);
