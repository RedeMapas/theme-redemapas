<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

$this->layout = 'home';

$heroLogos = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2025/10/logo-menu-rede-mapas.png';
$heroBanner = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2023/09/mapas2023-3.png';
$mapaBrasil = 'https://mapas.softwarelivre.tec.br/wp-content/uploads/sites/11/2023/08/quadrado-1.png';
$agendaBg = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2023/09/Prancheta_8_copia_8-removebg-preview.png';
$oportunidadesBg = 'https://mapa.softwarelivre.tec.br/wp-content/uploads/sites/11/2023/08/Mapas-2.png';
$agentesBg = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2023/09/Prancheta_8_copia_7-removebg-preview.png';
$espacosBg = 'https://mapas.softwarelivre.tec.br/wp-content/uploads/sites/11/2023/08/3.png';
$joinLeft = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2023/09/logo-mutirao-negativo.png';
$joinRight = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2023/09/logo-felicilab-negativo.png';
$circuitsImg = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2024/06/Mapas.jpg';
$circuitsLogo = 'https://mapa.softwarelivre.tec.br/wp-content/uploads/sites/11/2023/08/Mapas-2.png';
?>

<div class="redemapas-home-page" data-home-redemapas>
    <section class="hero">
        <div class="container hero__inner">
            <div class="hero__copy">
                <p class="kicker">Rede Mapas</p>
                <h1>MAPEAMENTO COLABORATIVO<br>COM TECNOLOGIA BRASILEIRA</h1>
                <p class="hero__description">A Rede Mapas conecta governos, universidades, empresas e sociedade civil para fortalecer a gestao colaborativa de informacoes de politicas publicas.</p>
                <div class="hero__logos">
                    <img src="<?= htmlspecialchars($heroLogos, ENT_QUOTES, 'UTF-8') ?>" alt="Logos institucionais">
                </div>
                <?php if (!$app->user->is('guest')): ?>
                <a class="btn btn--panel" href="<?= $app->createUrl('panel', 'index') ?>">
                    <?= \MapasCulturais\i::__('Acessar painel') ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="hero__art" role="img" aria-label="Grafismo colorido">
                <img src="<?= htmlspecialchars($heroBanner, ENT_QUOTES, 'UTF-8') ?>" alt="Banner grafico">
            </div>
        </div>
    </section>

    <section class="about" id="sobre">
        <div class="container about__inner">
            <div class="about__text">
                <h2>CONHECA A REDE MAPAS</h2>
                <p>A <strong>Rede Mapas</strong> e uma comunidade voltada ao uso e evolucao de solucoes digitais abertas para mapeamento e gestao colaborativa de dados territoriais.</p>
                <p>O ecossistema se estruturou em torno do <strong>Mapas Culturais</strong>, ampliando a interoperabilidade entre plataformas e fortalecendo principios de colaboracao, transparencia e dados abertos.</p>
                <a class="btn" href="#infos">Conhecer funcionalidades</a>
            </div>
            <div class="about__map">
                <img src="<?= htmlspecialchars($mapaBrasil, ENT_QUOTES, 'UTF-8') ?>" alt="Mapa do Brasil">
            </div>
        </div>
    </section>

    <section class="infos" id="infos">
        <div class="container">
            <h2>O QUE VOCE PODE FAZER COM O MAPAS</h2>
            <div class="entity-grid">
                <article class="entity-card">
                    <div class="entity-card__image" style="background-image: linear-gradient(90deg, rgba(38, 69, 166, 0.64), rgba(38, 69, 166, 0.64)), url('<?= htmlspecialchars($agendaBg, ENT_QUOTES, 'UTF-8') ?>')"><h3>Agenda</h3></div>
                    <div class="entity-card__body">
                        <p>Monitore eventos e programacoes para leitura territorial e acompanhamento de politicas publicas.</p>
                        <a class="entity-card__link" href="<?= $app->createUrl('search', 'events') ?>">Ver todas</a>
                    </div>
                </article>
                <article class="entity-card">
                    <div class="entity-card__image" style="background-image: linear-gradient(90deg, rgba(214, 22, 86, 0.68), rgba(214, 22, 86, 0.68)), url('<?= htmlspecialchars($oportunidadesBg, ENT_QUOTES, 'UTF-8') ?>')"><h3>Oportunidades</h3></div>
                    <div class="entity-card__body">
                        <p>Gerencie editais e fluxos de inscricao com processos colaborativos e rastreaveis.</p>
                        <a class="entity-card__link" href="<?= $app->createUrl('search', 'opportunities') ?>">Ver todas</a>
                    </div>
                </article>
                <article class="entity-card">
                    <div class="entity-card__image" style="background-image: linear-gradient(90deg, rgba(255, 122, 0, 0.68), rgba(255, 122, 0, 0.68)), url('<?= htmlspecialchars($agentesBg, ENT_QUOTES, 'UTF-8') ?>')"><h3>Agentes</h3></div>
                    <div class="entity-card__body">
                        <p>Cadastre pessoas e organizacoes em formato padronizado para analise e integracao de dados.</p>
                        <a class="entity-card__link" href="<?= $app->createUrl('search', 'agents') ?>">Ver todos</a>
                    </div>
                </article>
                <article class="entity-card">
                    <div class="entity-card__image" style="background-image: linear-gradient(90deg, rgba(237, 80, 16, 0.68), rgba(237, 80, 16, 0.68)), url('<?= htmlspecialchars($espacosBg, ENT_QUOTES, 'UTF-8') ?>')"><h3>Espacos</h3></div>
                    <div class="entity-card__body">
                        <p>Mapeie equipamentos e territorios para planejamento, monitoramento e controle social.</p>
                        <a class="entity-card__link" href="<?= $app->createUrl('search', 'spaces') ?>">Ver todos</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="map" id="mapa">
        <div class="container">
            <h2>CARTOGRAFIA COLABORATIVA DO TERRITORIO</h2>
            <p class="map__description">Visualize dados georreferenciados, fortaleça a transparencia e apoie decisoes baseadas em evidencias.</p>
            <div class="map__frame"></div>
        </div>
    </section>

    <section class="join">
        <img class="join-deco join-deco--left" src="<?= htmlspecialchars($joinLeft, ENT_QUOTES, 'UTF-8') ?>" alt="">
        <img class="join-deco join-deco--right" src="<?= htmlspecialchars($joinRight, ENT_QUOTES, 'UTF-8') ?>" alt="">
        <div class="container join__inner">
            <h2>ATIVE O MAPAS NO<br>SEU TERRITORIO</h2>
            <p>Implante uma plataforma colaborativa, conecte dados locais a uma rede nacional e qualifique a gestao publica com software livre.</p>
            <a class="btn btn--light" href="<?= $app->createUrl('auth', 'register') ?>">Quero participar da rede</a>
        </div>
    </section>

    <section class="notices" id="editais">
        <div class="container">
            <h2>NOTICIAS E ATUALIZACOES DA REDE</h2>
            <div class="notices__tabs">
                <button class="notices__tab notices__tab--active" type="button">Destaques</button>
                <button class="notices__tab" type="button">Comunicados</button>
            </div>
            <ul class="notice-list">
                <li><span class="tag">Rede</span> Ecossistema em expansao com novas instituicoes e servicos colaborativos</li>
                <li><span class="tag">Dados</span> Interoperabilidade e padroes abertos para integracao de plataformas</li>
                <li><span class="tag">Gestao</span> Solucoes para mapeamento, fomento, monitoramento e avaliacao de politicas</li>
            </ul>
        </div>
    </section>

    <section class="circuits" id="circuitos">
        <div class="container circuits__inner">
            <div class="circuits__text">
                <h2>SOFTWARE LIVRE, COMUNIDADE E EVOLUCAO CONTINUA</h2>
                <p>O Mapas e desenvolvido em comunidade, com governanca colaborativa, compartilhamento de conhecimento e melhoria constante das solucoes digitais.</p>
                <a class="btn" href="https://rede.mapas.tec.br/" target="_blank" rel="noopener noreferrer">Conhecer a Rede Mapas</a>
            </div>
            <div class="circuits__art">
                <img src="<?= htmlspecialchars($circuitsImg, ENT_QUOTES, 'UTF-8') ?>" alt="Circuitos artisticos">
                <img class="circuits__logo" src="<?= htmlspecialchars($circuitsLogo, ENT_QUOTES, 'UTF-8') ?>" alt="Rede Mapas">
            </div>
        </div>
    </section>
</div>
