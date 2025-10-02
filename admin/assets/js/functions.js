(() => {
  'use strict'
  const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(tooltipTriggerEl => {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })
})()

  const wrap = document.getElementById('inputs-galeria');
  const btn  = document.getElementById('btn-add-galeria');
  const prev = document.getElementById('preview-galeria');

  function bindPreview(input){
    input.addEventListener('change', () => {
      for (const file of input.files){
        if (!file.type.startsWith('image/')) continue;
        const reader = new FileReader();
        reader.onload = e => {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.style.width = '120px';
          img.style.height = '90px';
          img.style.objectFit = 'cover';
          img.className = 'rounded border';
          prev.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    });
  }
  bindPreview(wrap.querySelector('input[type=file]'));
  btn.addEventListener('click', () => {
    const inp = document.createElement('input');
    inp.type = 'file';
    inp.name = 'galeria[]';
    inp.className = 'form-control';
    inp.accept = 'image/*';
    wrap.appendChild(inp);
    bindPreview(inp);
  });
