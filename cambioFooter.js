function hayVideojuegos() {
  // Aquí puedes agregar la lógica para determinar si hay videojuegos disponibles
  // Por ejemplo, podrías verificar si hay elementos en el carrito de compras o si hay videojuegos en tu base de datos
  // Por ahora, devolveré true para este ejemplo
  return true;
}

// Función para cambiar la posición del footer
function cambiarPosicionFooter() {
  const footer = document.getElementById('miFooter');
  
  // Si hay videojuegos disponibles, cambia la posición del footer a sticky
  if (hayVideojuegos()) {
      footer.style.position = 'sticky';
      footer.style.bottom = '0';
  } else {
      // Si no hay videojuegos disponibles, cambia la posición del footer a absolute
      footer.style.position = 'absolute';
      footer.style.bottom = '0';
  }
}

// Llama a la función al cargar la página para establecer la posición inicial del footer
cambiarPosicionFooter();