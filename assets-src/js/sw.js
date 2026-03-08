// src/themes/RedeMapas/assets-src/js/sw.js
'use strict';

self.addEventListener('install', function () {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(clients.claim());
});

self.addEventListener('push', function (event) {
    var data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = {};
    }

    var title = data.title || 'Nova notificação';
    var options = {
        body: data.body || '',
        icon: data.icon || '/favicon.png',
        badge: data.badge || '/favicon.png',
        data: {
            url: data.url || '/painel'
        }
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    var targetUrl = new URL(
        (event.notification.data && event.notification.data.url)
            ? event.notification.data.url
            : '/painel',
        self.location.origin
    ).href;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (windowClients) {
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
