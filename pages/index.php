<?php
$pageTitle = 'Inicio';
include __DIR__ . '/../partials/head.php';
?>

<!-- carrusel -->
<div id="carouselExampleCaptions" class="carousel slide shadow-bottom" data-bs-ride="carousel" data-bs-interval="3000">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../assets/img/carousel_1.jpg" class="d-block w-100" alt="servicio_desayunos">
      <div class="carousel-caption ">
        <h1>Nuevo servicio de desayunos</h1>
        <p>Comprometidos con el bienestar y la salud de los menores</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/carousel_2.png" class="d-block w-100" alt="alberca">
      <div class="carousel-caption ">
        <h1>Nueva alberca exclusiva para los menores</h1>
        <p>Consulta requisitos para la Inscripción</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/carousel_3.jpg" class="d-block w-100" alt="vacunación">
      <div class="carousel-caption ">
        <h1>Calendario de vacunación infantil 2026</h1>
        <p>Revisa fechas de aplicación y reposiciones</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="../assets/img/carousel_4.png" class="d-block w-100" alt="IA">
      <div class="carousel-caption ">
        <h1>Nuevo plan de estudios con Inteligencia Artificial</h1>
        <p>Comprometidos con el futuro de la educación</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div class="b-example-divider"></div>

<!-- banner presentación -->
<div class="px-4 pt-5 my-4 text-center border-bottom shadow-lg">
  <h2 class="display-4 fw-bold text-body-emphasis">
    Coordinación de Centros de Desarrollo Infantil
  </h2>

  <div class="col-lg-6 mx-auto">
    <p class="lead mb-4 " id="banner-text-pres">
          Se encarga de coordinar y dirigir el control y operación de los trámites correspondientes para el otorgamiento de la prestación del servicio de guardería a las madres y padres trabajadores del Instituto Politécnico Nacional, así como supervisar la gestión de las prestaciones al personal de la Coordinación y de los Centros de Desarrollo Infantil, en los términos de la normatividad aplicable.
    </p>
  </div>
  <div class="overflow-hidden" >
    <div class="container px-5">
      <img
        src="../assets/img/banner_img.png"
        class="img-fluid mb-5 "
        alt="Example image"
        width="300"
        loading="lazy"
        
      >
    </div>
  </div>
</div>

<div class="b-example-divider"></div>

<!-- banner contador -->
<div class="container col-xl-10 col-xxl-8 px-4 py-5">
  <div class="row align-items-center g-5 py-5">

    <!-- Texto -->
    <div class="col-lg-7 text-center text-lg-start">
      <h1 class="display-4 fw-bold lh-1 mb-3">
        Atención personalizada y staff capacitado
      </h1>

      <p class="fs-4 text-justify ">
        En todas nuestras cedes contamos con personal para brindar las atenciones necesarias; nuestras encuestas
        de satisfacción y nuestros años de experiencia nos respaldan.
      </p>
    </div>

    <!-- Contadores -->
    <div class="col-lg-5 contador">
      <div class="row g-4 text-center">

        <div class="col-6">
          <div class="p-4 bg-light rounded-4 shadow-sm h-100">
            <span
              class="numero display-2 fw-bold  d-block"
              data-target="200"
            >0</span>
            <p class="mb-0 text-muted">Niños y niñas</p>
          </div>
        </div>

        <div class="col-6">
          <div class="p-4 bg-light rounded-4 shadow-sm h-100">
            <span
              class="numero display-2 fw-bold  d-block"
              data-target="98"
            >0</span>
            <p class="mb-0 text-muted">Satisfacción (%)</p>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>


<div class="b-example-divider"></div>

<!-- banner concurso -->

<div class="px-4 pt-5 my-5 text-center border-bottom">
    <h1 class="display-4 fw-bold">Concurso de alebrijes</h1>
    <div class="col-lg-6 mx-auto">
      <p class="lead mb-4 fs-4">Como actividad cultural especial de el mes te invitamos a participar en nuestro concurso anual de alebrijes, en el que se reúnen las mejores esculturas de entre todas nuestras sedes.</p>
        <div>
          <iframe width="75%" height="315" src="https://www.youtube.com/embed/_76w0e-YMnE?si=rInwncsSCNLwJUE4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
  </div>

<div class="b-example-divider"></div>


<!-- banner actividades culturales -->
<div class="container col-xl-10 px-4 py-5" id="culturales">
  <div class="row align-items-center g-5 py-5">
    
    <!-- Columna del texto -->
    <div class="col-lg-5 text-center text-lg-start culturales-text-container">
      <h1 class="fw-bold lh-1 mb-3 culturales-title">Actividades culturales en todas nuestras sedes</h1>
      <p class="col-lg-10 fs-4 culturales-text">
        Contamos con varias actividades como complemento para la educación de los menores.
      </p>
    </div>

    <!-- Columna de la tarjeta -->
    <div class="col-lg-7 d-flex justify-content-center">
      <div class="fade-edge-radial w-100 d-flex flex-column flex-lg-row align-items-center">

        <!-- Imagen -->
        <div id="banner-img-container">
          <img src="../assets/img/acuarela_c1.png" class="rounded-start mb-3 mb-lg-0 me-lg-3" alt="Acuarela" id="banner-img">
        </div>

        <!-- Contenido de la tarjeta -->
        <div class="card-body text-center text-lg-start">
          <h5 class="card-title fs-2" id="banner-title">Acuarela</h5>
          <p class="card-text fs-5" id="banner-desc">
            Imparte: Prof. Ana López<br>
            Horario: Lunes y miércoles, 4:00–6:00 PM
          </p>
        </div>

      </div>
    </div>

  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>