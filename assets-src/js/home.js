(function () {
  // Carrega contagem de notificações pendentes
  var bell = document.querySelector('[data-redemapas-notifications]');
  if (bell) {
    var mapas = globalThis.Mapas || null;
    var baseURL = (mapas && mapas.baseURL) ? mapas.baseURL : '/';
    fetch(baseURL + 'api/notification/find?@count=1&status=1', {
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
})();
