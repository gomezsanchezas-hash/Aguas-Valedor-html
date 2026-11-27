// ==========================================
// 1. VALIDACIÓN EN TIEMPO REAL (Solo en página de contacto)
// ==========================================
const correo = document.getElementById("correo");
const mensajeError = document.getElementById("mensaje-error");
const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (correo && mensajeError) {
  correo.addEventListener("input", function () {
    const valor = correo.value.trim();
  
    if (valor === "") {
      correo.style.border = "";
      mensajeError.style.display = "none";
      return;
    }

    if (regexCorreo.test(valor)) {
      correo.style.border = "5px solid green";
      mensajeError.style.display = "none";
    } else {
      correo.style.border = "5px solid red";
      mensajeError.textContent =
        "El correo debe tener un formato válido. Ejemplo: usuario@correo.com";
      mensajeError.style.display = "block";
    }
  });
}

// ==========================================
// 2. MENÚ HAMBURGUESA Y FILTROS DEL CATÁLOGO
// ==========================================
const btnCategorias = document.getElementById('btn-categorias');
const listaCategorias = document.getElementById('lista-categorias');

const productosAgua = document.querySelectorAll('.agua');
const productosLeche = document.querySelectorAll('.leche');
const productosExoticos = document.querySelectorAll('.exoticos');

const botonLeche = document.getElementById('boton-leche');
const botonAgua = document.getElementById('boton-agua');
const botonExoticos = document.getElementById('boton-exoticos');
const botonTodos = document.getElementById('boton-todos');

// Abrir/cerrar menú hamburguesa
if (btnCategorias && listaCategorias) {
  btnCategorias.addEventListener('click', () => {
    listaCategorias.classList.toggle('abierto');
  });
}

function cambiarVisibilidad(listaElementos, estado) {
  listaElementos.forEach((elemento) => {
    elemento.style.display = estado;
  });
}

// Botones de filtro
if (botonLeche && botonAgua && botonExoticos) {
  botonLeche.addEventListener('click', () => {
    cambiarVisibilidad(productosLeche, 'block');
    cambiarVisibilidad(productosAgua, 'none');
    cambiarVisibilidad(productosExoticos, 'none');
    listaCategorias.classList.remove('abierto');
  });

  botonAgua.addEventListener('click', () => {
    cambiarVisibilidad(productosAgua, 'block');
    cambiarVisibilidad(productosLeche, 'none');
    cambiarVisibilidad(productosExoticos, 'none');
    listaCategorias.classList.remove('abierto');
  });

  botonExoticos.addEventListener('click', () => {
    cambiarVisibilidad(productosExoticos, 'block');
    cambiarVisibilidad(productosLeche, 'none');
    cambiarVisibilidad(productosAgua, 'none');
    listaCategorias.classList.remove('abierto');
  });

  if (botonTodos) {
    botonTodos.addEventListener('click', () => {
      cambiarVisibilidad(productosLeche, 'block');
      cambiarVisibilidad(productosAgua, 'block');
      cambiarVisibilidad(productosExoticos, 'block');
      listaCategorias.classList.remove('abierto');
    });
  }
}