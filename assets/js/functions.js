const toggleBtn = document.querySelector('.nav-toggle');
const navMenu = document.querySelector('.nav-menu');

toggleBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  navMenu.classList.toggle('active');
});
document.addEventListener('click', (e) => {
  if (!navMenu.contains(e.target) && !toggleBtn.contains(e.target)) {
    navMenu.classList.remove('active');
  }
});

function showLang(lang) {
  fetch(`includes/idioma/termos-${lang}.php`)
    .then(res => res.text())
    .then(data => {
      document.getElementById("conteudo-termos").innerHTML = data;
    });
}

document.addEventListener('DOMContentLoaded', () => {
  const termosCheck = document.getElementById('termosCheck');
  const confirmarBtn = document.getElementById('confirmarPedido');
  const aceitarBtn = document.getElementById('aceitarTermos');
  const modalTermos = new bootstrap.Modal(document.getElementById('modalTermos'));


  termosCheck.addEventListener('change', (e) => {
    if (termosCheck.checked) {
      termosCheck.checked = false;
      modalTermos.show();
    } else {

      confirmarBtn.disabled = true;
    }
  });


  document.querySelector('.termos-link').addEventListener('click', () => {
    modalTermos.show();
  });


  aceitarBtn.addEventListener('click', () => {
    termosCheck.checked = true;
    confirmarBtn.disabled = false;
    modalTermos.hide();
  });

  document.querySelectorAll('.flag').forEach(flag => {
    flag.addEventListener('click', () => {
      const lang = flag.getAttribute('data-lang');
      fetch(`includes/idioma/termos-${lang}.php`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('modal-termos-conteudo').innerHTML = html;
        });
    });
  });
});
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.toast').forEach(toastEl => {
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  });
});

document.querySelectorAll(".btn-info-produto").forEach(btn => {
    btn.addEventListener("click", function() {
        const nome = this.getAttribute("data-nome");
        const descricao = this.getAttribute("data-descricao") || "Sem descrição";
        const preco = this.getAttribute("data-preco");
        const tamanho = this.getAttribute("data-tamanho") || "N/D";

        document.getElementById("modalInfoContent").innerHTML = `
            <h4>${nome}</h4>
            <p><strong>Descrição:</strong> ${descricao}</p>
            <p><strong>Tamanho:</strong> ${tamanho} MB</p>
            <p><strong>Preço:</strong> R$ ${preco}</p>
        `;
    });
});