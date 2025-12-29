const offcanvasElement = document.getElementById('menuOffcanvas');

window.addEventListener('resize', () => {
  if (window.innerWidth >= 992 && offcanvasElement.classList.contains('show')) {
    const instance = bootstrap.Offcanvas.getInstance(offcanvasElement);
    instance.hide();
  }
});
const contadores = document.querySelectorAll('.numero');

const animarContador = (contador) => {
    const objetivo = +contador.getAttribute('data-target');
    let actual = 0;
    const incremento = objetivo / 200;

    const actualizar = () => {
        actual += incremento;

        if (actual < objetivo) {
            contador.textContent = Math.ceil(actual);
            requestAnimationFrame(actualizar);
        } else {
            contador.textContent = objetivo;
        }
    };

    actualizar();
};

const observer = new IntersectionObserver((entradas, observer) => {
    entradas.forEach(entrada => {
        if (entrada.isIntersecting) {
            animarContador(entrada.target);
            observer.unobserve(entrada.target); 
        }
    });
}, {
    threshold: 0.5
});

contadores.forEach(contador => {
    observer.observe(contador);
});

window.addEventListener('scroll', () => {
  const scroll = window.scrollY;
  document.querySelectorAll('.carousel-item.active img')
    .forEach(img => {
      img.style.transform = `translateY(${scroll * 0.2}px) scale(1.1)`;
    });
});



const banners = [
  {
    img: '../assets/img/chess_c2.png',
    title: 'Ajedrez',
    desc: 'Imparte: Prof. Luis Ortega, Horario: Sábados, 10:00 AM–12:00 PM '
  },
  {
    img: '../assets/img/ballet_c3.png',
    title: 'Ballet',
    desc: 'Imparte: Mtra. Sofía Herrera, Horario: Viernes, 2:00–4:00 PM '
  },
  {
    img: '../assets/img/acuarela_c1.png',
    title: 'Acuarela',
    desc: 'Imparte: Imparte: Prof. Ana López, Horario: Lunes y miércoles, 4:00–6:00 PM'
  },
  {
    img: '../assets/img/cocina_c4.png',
    title: 'Cocina',
    desc: 'Imparte: Ing. Carlos Méndez, Horario: Martes y jueves, 3:00–5:00 PM '
  },
   
];

let i = 0;

setInterval(() => {
  // Primero hacemos fadeOut de la imagen
  $('#banner-img').fadeOut(300, function () {
    // Cambiamos la imagen
    $('#banner-img').attr('src', banners[i].img);
    // Cambiamos el título y la descripción al mismo tiempo
    $('#banner-title').text(banners[i].title);
    $('#banner-desc').text(banners[i].desc);
    // Luego hacemos fadeIn de todo
    $('#banner-img, #banner-title, #banner-desc').fadeIn(300);
    
    // Actualizamos el índice
    i = (i + 1) % banners.length;
  });
}, 3000);




