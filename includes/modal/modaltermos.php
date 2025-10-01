   <div class="modal fade" id="modalTermos" tabindex="-1" aria-labelledby="modalTermosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white rounded-4 border-0">

                <div class="modal-header border-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="modalTermosLabel">Termos de Serviço</h5>
                    <div class="flags d-flex gap-2">
                        <img src="assets/img/br.png" alt="Português" class="flag" data-lang="pt" style="cursor:pointer; width:24px;">
                        <img src="assets/img/us.png" alt="English" class="flag" data-lang="en" style="cursor:pointer; width:24px;">
                        <img src="assets/img/es.png" alt="Español" class="flag" data-lang="es" style="cursor:pointer; width:24px;">
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body" id="modal-termos-conteudo">
                    <?php include "includes/idioma/termos-pt.php"; ?>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-warning" id="aceitarTermos">Concordo com os Termos</button>
                </div>
            </div>
        </div>
    </div>