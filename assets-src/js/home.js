import { fetchOpportunities } from './services/home-api.js';

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric' });
}

function renderOpportunityCard(op) {
    const deadline = op.registrationTo ? `Até ${formatDate(op.registrationTo)}` : 'Prazo a confirmar';
    const org = op.ownerEntity?.name ?? '';
    return `
        <article class="opp-card">
            <div class="opp-card__header">
                <span class="opp-card__deadline">${deadline}</span>
            </div>
            <div class="opp-card__body">
                <h3 class="opp-card__title">${op.name}</h3>
                ${org ? `<p class="opp-card__org">${org}</p>` : ''}
                ${op.shortDescription ? `<p class="opp-card__desc">${op.shortDescription}</p>` : ''}
            </div>
            <a class="opp-card__link" href="/opportunity/${op.id}/info">Ver edital</a>
        </article>
    `;
}

async function loadOpportunities() {
    const grid = document.querySelector('[data-opportunities-grid]');
    if (!grid) return;

    const opps = await fetchOpportunities(3).catch(() => []);

    if (!opps.length) {
        grid.innerHTML = `
            <div class="opportunities__empty">
                <p>Nenhum edital aberto no momento.</p>
                <p>Ative as notificações para ser avisado quando surgirem novos editais.</p>
            </div>
        `;
        return;
    }

    grid.innerHTML = opps.map(renderOpportunityCard).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    loadOpportunities();

    // Carrega contagem de notificações pendentes
    var bell = document.querySelector('[data-redemapas-notifications]');
    if (bell) {
        var mapas = globalThis.Mapas || null;
        var baseURL = (mapas && mapas.baseURL) ? mapas.baseURL : '/';
        fetch(baseURL + 'api/notification/find?@count=1&status=EQ(1)', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (r) { return r.json(); })
            .then(function (count) {
                if (count > 0) {
                    var badge = bell.querySelector('[data-redemapas-notifications-count]');
                    if (badge) {
                        badge.textContent = count > 99 ? '99+' : String(count);
                        badge.hidden = false;
                    }
                }
            })
            .catch(function () {});
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            var href = link.getAttribute('href');
            if (!href || href === '#') {
                return;
            }
            var target = document.querySelector(href);
            if (!target) {
                return;
            }
            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
