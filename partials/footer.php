<?php
require_once __DIR__ . '/../config.php';
?>

</main>

<footer class="d-flex flex-wrap justify-content-between align-items-center py-4 my-4 border-top bg-light rounded px-5">
  <p class="col-12 col-md-4 mb-0 text-muted order-1 order-md-1">
    © 2026 Tecnologías Para EL Desarrollo de Aplicaciones Web Equipo 4
  </p>

  <a href="https://www.gob.mx/segob" target="_blank" class="col-12 col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 text-decoration-none order-0 order-md-2">
    <img src="<?= asset('assets/img/logo_equipo4_transparente.png'); ?>" alt="logo_equipo4_transparente" width="50">
  </a>

  <ul class="nav col-12 col-md-4 justify-content-end justify-content-md-end order-2 order-md-3">
    <li class="nav-item"><a href="https://www.gob.mx/" target="_blank" class="nav-link px-2 text-muted">Trámites</a></li>
    <li class="nav-item"><a href="https://www.gob.mx/gobierno" target="_blank" class="nav-link px-2 text-muted">Gobierno</a></li>
    <li class="nav-item"><a href="https://www.ipn.mx/secadmin/certificaci%c3%b3n-iso/tr%c3%adpticos-sgi.html" target="_blank" class="nav-link px-2 text-muted">Trípticos</a></li>
    <li class="nav-item"><a href="https://www.ipn.mx/secadmin/certificacion-iso/denuncia-anonima.html" target="_blank" class="nav-link px-2 text-muted">Denuncia anónima</a></li>
    <li class="nav-item"><a href="http://www.ordenjuridico.gob.mx/" target="_blank" class="nav-link px-2 text-muted">Marco jurídico</a></li>
  </ul>
</footer>

<?php if (!empty($pageScripts) && is_array($pageScripts)): ?>
  <?php foreach ($pageScripts as $script): ?>
    <script src="<?= asset($script); ?>" defer></script>
  <?php endforeach; ?>
<?php endif; ?>

</body>
</html>