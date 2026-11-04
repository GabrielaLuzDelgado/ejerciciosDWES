// Diálogo de confirmación: cualquier <form data-confirmar="mensaje"> pide confirmación antes de enviarse.
(() => {
  const dialogo = document.getElementById('dialogo-confirmar');
  const texto = document.getElementById('dialogo-texto');
  if (!dialogo || !texto) return;

  let formulario = null;

  document.addEventListener('submit', (e) => {
    const mensaje = e.target.dataset?.confirmar;
    if (!mensaje) return;                            // los demás formularios no se tocan
    e.preventDefault();
    if (typeof dialogo.showModal !== 'function') {   // navegador sin <dialog>: plan B
      if (confirm(mensaje)) e.target.submit();
      return;
    }
    formulario = e.target;
    texto.textContent = mensaje;
    dialogo.showModal();
  });

  dialogo.addEventListener('close', () => {
    if (dialogo.returnValue === 'confirmar' && formulario) formulario.submit();
    formulario = null;
    dialogo.returnValue = '';
  });
})();