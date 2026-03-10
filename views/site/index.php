<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

$this->layout = 'home';

$circuitsImg = 'https://rede.mapas.tec.br/wp-content/uploads/sites/11/2024/06/Mapas.jpg';
?>

<div class="redemapas-home-page" data-home-redemapas>
    <section class="hero">
        <div class="hero__inner">
            <p class="kicker">Rede Mapas</p>
            <h1>Mapeamento colaborativo<br>para políticas públicas</h1>
            <p class="hero__description">Conectamos governos, universidades e agentes culturais para mapear e fortalecer territórios com dados abertos.</p>
            <?php if (!$app->user->is('guest')): ?>
            <a class="btn btn--panel" href="<?= $app->createUrl('panel', 'index') ?>">
                <?= \MapasCulturais\i::__('Acessar painel') ?>
            </a>
            <?php endif; ?>
            <span class="hero__scroll-cue" aria-hidden="true">↓</span>
        </div>
    </section>

    <section class="split">
        <a class="split__card split__card--cultural" href="#explorar" aria-label="Sou agente cultural — explorar conteúdo">
            <div class="split__card-inner">
                <span class="split__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                </span>
                <h2>Sou agente cultural</h2>
                <p>Descubra editais abertos, espaços culturais e eventos perto de você.</p>
                <span class="split__cta">Explorar conteúdo →</span>
            </div>
        </a>
        <a class="split__card split__card--gestor" href="#gestores" aria-label="Sou gestor público — conhecer a solução">
            <div class="split__card-inner">
                <span class="split__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                </span>
                <h2>Sou gestor público</h2>
                <p>Veja como municípios usam a plataforma para gestão de políticas públicas.</p>
                <span class="split__cta">Conhecer a solução →</span>
            </div>
        </a>
    </section>

    <section class="opportunities" id="explorar">
        <div class="container">
            <div class="section-header">
                <h2>Editais abertos</h2>
                <a class="section-header__link" href="<?= $app->createUrl('search', 'opportunities') ?>">Ver todos →</a>
            </div>
            <div class="opportunities__grid" data-opportunities-grid>
                <div class="opportunities__loading" aria-live="polite">Carregando editais...</div>
            </div>
        </div>
    </section>

    <section class="gestores" id="gestores">
        <div class="container">
            <div class="section-header">
                <h2>Para gestores públicos</h2>
            </div>
            <p class="gestores__intro">Como o Mapas Culturais transforma a gestão territorial de políticas públicas.</p>
            <div class="gestores__grid">
                <div class="gestores__feature">
                    <span class="gestores__feature-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    <h3>Fomento e editais</h3>
                    <p>Gerencie chamadas públicas com fluxos de inscrição colaborativos e rastreáveis.</p>
                </div>
                <div class="gestores__feature">
                    <span class="gestores__feature-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </span>
                    <h3>Monitoramento e avaliação</h3>
                    <p>Acompanhe indicadores territoriais em tempo real com dados georreferenciados.</p>
                </div>
                <div class="gestores__feature">
                    <span class="gestores__feature-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </span>
                    <h3>Transparência pública</h3>
                    <p>Dados abertos e auditáveis por cidadãos, parceiros e organizações de controle.</p>
                </div>
                <div class="gestores__feature">
                    <span class="gestores__feature-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/></svg>
                    </span>
                    <h3>Integração de dados</h3>
                    <p>Interoperabilidade com outras plataformas via API aberta e padrões de dados.</p>
                </div>
            </div>
            <div class="gestores__cta">
                <a class="btn btn--panel" href="<?= $app->createUrl('auth', 'register') ?>">Quero ativar no meu município</a>
            </div>
        </div>
    </section>

    <section class="map-section" id="mapa">
        <div class="container">
            <h2>Cartografia do território brasileiro</h2>
            <p class="map-section__description">Dados georreferenciados de agentes, espaços e eventos em todo o Brasil.</p>
            <div class="map-section__frame">
                <iframe
                    src="<?= $app->createUrl('search', 'agents') ?>"
                    title="Mapa colaborativo Rede Mapas"
                    loading="lazy"
                    allowfullscreen
                ></iframe>
            </div>
            <div class="map-section__footer">
                <a class="btn" href="<?= $app->createUrl('search', 'agents') ?>">Abrir mapa completo →</a>
            </div>
        </div>
    </section>

    <section class="community" id="comunidade">
        <div class="container community__inner">
            <div class="community__text">
                <h2>Software livre, comunidade e evolução contínua</h2>
                <p>O Mapas Culturais é desenvolvido em comunidade, com governança colaborativa, compartilhamento de conhecimento e melhoria constante das soluções digitais.</p>
                <p>Faça parte do ecossistema: contribua com código, use a plataforma e ajude a mapear o território brasileiro.</p>
                <a class="btn" href="https://rede.mapas.tec.br/" target="_blank" rel="noopener noreferrer">Conhecer a Rede Mapas</a>
            </div>
            <div class="community__art">
                <img src="<?= htmlspecialchars($circuitsImg, ENT_QUOTES, 'UTF-8') ?>" alt="Comunidade Rede Mapas" loading="lazy">
            </div>
        </div>
    </section>

    <section class="cta-footer">
        <div class="container cta-footer__inner">
            <h2>Pronto para começar?</h2>
            <div class="cta-footer__actions">
                <a class="btn cta-footer__btn--primary" href="<?= $app->createUrl('auth', 'register') ?>">
                    Cadastre-se grátis
                </a>
                <a class="btn cta-footer__btn--secondary" href="https://rede.mapas.tec.br/" target="_blank" rel="noopener noreferrer">
                    Fale com nossa equipe
                </a>
            </div>
            <p class="cta-footer__hint">Cadastre-se para agentes culturais · Fale conosco para gestores públicos</p>
        </div>
    </section>
</div>
