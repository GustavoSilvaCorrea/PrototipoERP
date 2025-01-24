const backgrounds = [
  'js/img/fundo_login.jpeg',
  'js/img/fundo_login2.jpeg'
  // Adicione mais URLs de imagens conforme necessário
];

function changeBackground() {
  const randomIndex = Math.floor(Math.random() * backgrounds.length);
  document.body.style.backgroundImage = `url('${backgrounds[randomIndex]}')`;
}

// Mude a imagem de fundo quando a página for carregada
document.addEventListener('DOMContentLoaded', changeBackground);
