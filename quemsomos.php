<?php include 'conexao.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/head.php' ?>
</head>

<body>
    <?php include 'includes/header.php' ?>

    <section class="apresent2 d-flex align-items-center">
        <div class="container">
            <div class="text-center">
                <h1>Quem somos?</h1>
            </div>
        </div>
    </section>
    <section class="sobre">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="assets/img/fundo.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700"
                        height="500" loading="lazy" />
                </div>
                <div class="col-lg-6">
                    <span class="text-uppercase textmut small">Especialista em modelagens</span>
                    <h1 class="display-5 fw-bold  text-white lh-1 mb-3 mt-2">
                        Lodz Mapper — Realismo, Criatividade e Funcionalidade para o seu Servidor
                    </h1>
                    <p class="lead text-white">
                        A Lodz Mapper é uma loja especializada em mapas & modelagens para servidores de MTA:SA, com foco total em imersão, leveza e autenticidade. Atuamos com criações detalhadas que valorizam a identidade e a jogabilidade do seu projeto, seja ele focado em roleplay, servidor casual ou temático.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="carouselquem mt-3 mb-3 position-relative">
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="4000">
                    <img src="assets/img/entrada.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="assets/img/footer.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                    <img src="assets/img/ilegal.png" class="d-block w-100" alt="...">
                </div>
            </div>

            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                <div>
                    <span class="text-uppercase textmut small">MAPAS COM QUALIDADE!</span>
                    <h2 class="fw-bold">NOSSA MISSÃO</h2>
                    <p class="">
                        Na Lodz Mapper, nossa missão é transformar servidores de MTA:SA em experiências únicas, combinando realismo, criatividade e desempenho. Buscamos sempre entregar mapas e modelagens que não apenas atendam às necessidades dos nossos clientes, mas que também elevem a jogabilidade, garantindo autenticidade, leveza e imersão em cada projeto.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="team-section py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-5">
                    <span class="text-uppercase textmut small">QUEM FAZ ACONTECER</span>
                    <h2 class="fw-bold text-white mt-2">CONHEÇA O TIME</h2>
                    <p class="text-white">
                        Na <b>Lodz Mapper</b>, nosso time é formado por criadores apaixonados por mapas e modelagens para servidores de MTA:SA.
                        Juntos, unimos criatividade, técnica e experiência para entregar projetos únicos, com realismo, leveza e autenticidade,
                        garantindo sempre a melhor jogabilidade para o seu servidor.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="card text-white border-0 shadow-sm">
                                <img src="assets/img/membro1.jpg" class="card-img" alt="...">
                                <div class="card-img-overlay d-flex flex-column justify-content-end bg-dark bg-opacity-50">
                                    <h5 class="card-title">Murilo Lodi</h5>
                                    <p class="card-text small">Fundador & Mapper</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card text-white border-0 shadow-sm">
                                <img src="assets/img/membro2.jpg" class="card-img" alt="...">
                                <div class="card-img-overlay d-flex flex-column justify-content-end bg-dark bg-opacity-50">
                                    <h5 class="card-title">Fulano</h5>
                                    <p class="card-text small">Modelador 3D</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card text-white border-0 shadow-sm">
                                <img src="assets/img/membro3.jpg" class="card-img" alt="...">
                                <div class="card-img-overlay d-flex flex-column justify-content-end bg-dark bg-opacity-50">
                                    <h5 class="card-title">Ciclano</h5>
                                    <p class="card-text small">Designer & Otimizador</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card text-white border-0 shadow-sm">
                                <img src="assets/img/membro4.jpg" class="card-img" alt="...">
                                <div class="card-img-overlay d-flex flex-column justify-content-end bg-dark bg-opacity-50">
                                    <h5 class="card-title">Beltrano</h5>
                                    <p class="card-text small">Suporte & Atendimento</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php' ?>
    <?php include 'includes/scripts.php' ?>
</body>

</html>