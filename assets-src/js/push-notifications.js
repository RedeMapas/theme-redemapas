(function () {
    'use strict';

    var deferredInstallPrompt = null;
    var initialized = false;

    function getConfig() {
        var mapas = globalThis.Mapas || null;
        return (mapas && mapas.redemapasPush) ? mapas.redemapasPush : null;
    }

    function getString(key, fallback) {
        var config = getConfig();
        return (config && config.strings && config.strings[key]) ? config.strings[key] : fallback;
    }

    function toUint8Array(base64String) {
        var padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        var base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        var raw = atob(base64);
        var output = new Uint8Array(raw.length);
        for (var i = 0; i < raw.length; ++i) {
            output[i] = raw.charCodeAt(i);
        }
        return output;
    }

    async function postJson(url, payload) {
        var response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });
        if (!response.ok) {
            throw new Error('Push request failed: ' + response.status);
        }
        return response;
    }

    async function ensureServiceWorker(config) {
        if (!config || !config.serviceWorkerUrl || !('serviceWorker' in navigator)) {
            return null;
        }
        try {
            return await navigator.serviceWorker.register(config.serviceWorkerUrl, { scope: '/' });
        } catch (error) {
            console.warn('[redemapas-push] SW register failed', error);
            return null;
        }
    }

    async function subscribePush(config) {
        if (!config || !config.enabled) {
            throw new Error('Push not enabled');
        }
        var registration = await ensureServiceWorker(config);
        if (!registration) {
            throw new Error('Service worker registration failed');
        }
        var existing = await registration.pushManager.getSubscription();
        var subscription = existing || await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: toUint8Array(config.publicKey)
        });
        await postJson(config.subscribeUrl, { subscription: subscription.toJSON() });
    }

    function setupInstallTriggers() {
        var triggers = document.querySelectorAll('[data-redemapas-install]');
        if (!triggers.length) return;

        globalThis.addEventListener('beforeinstallprompt', function (event) {
            event.preventDefault();
            deferredInstallPrompt = event;
            triggers.forEach(function (el) { el.hidden = false; el.style.display = ''; });
        });

        globalThis.addEventListener('appinstalled', function () {
            deferredInstallPrompt = null;
            triggers.forEach(function (el) { el.hidden = true; });
        });

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', async function () {
                if (!deferredInstallPrompt) {
                    alert(getString('installApp', 'Instalar aplicativo') + ': use o menu do navegador (Adicionar à tela inicial).');
                    return;
                }
                deferredInstallPrompt.prompt();
                await deferredInstallPrompt.userChoice;
                deferredInstallPrompt = null;
                triggers.forEach(function (el) { el.hidden = true; });
            });
        });
    }

    function setupPushTriggers() {
        var config = getConfig();
        var mapas = globalThis.Mapas || null;
        var userLogged = !!(mapas && mapas.userId);
        var triggers = document.querySelectorAll('[data-redemapas-push]');

        if (!triggers.length || !userLogged) return;

        if (!('Notification' in globalThis) || !('serviceWorker' in navigator) || !('PushManager' in globalThis)) {
            triggers.forEach(function (el) {
                el.setAttribute('disabled', '');
                el.textContent = getString('unsupported', 'Notificações não suportadas');
            });
            return;
        }

        if (!config || !config.enabled) {
            triggers.forEach(function (el) {
                el.setAttribute('disabled', '');
                el.textContent = getString('unavailable', 'Notificações indisponíveis');
            });
            return;
        }

        if (Notification.permission === 'granted') {
            subscribePush(config).catch(function (e) { console.warn('[redemapas-push]', e); });
            triggers.forEach(function (el) {
                el.setAttribute('disabled', '');
                el.textContent = getString('enabled', 'Notificações ativadas');
            });
            return;
        }

        if (Notification.permission === 'denied') {
            triggers.forEach(function (el) {
                el.setAttribute('disabled', '');
                el.textContent = getString('blocked', 'Notificações bloqueadas');
            });
            return;
        }

        triggers.forEach(function (trigger) {
            trigger.textContent = getString('enable', 'Ativar notificações');
            trigger.addEventListener('click', async function () {
                try {
                    var permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        await subscribePush(config);
                        triggers.forEach(function (el) {
                            el.setAttribute('disabled', '');
                            el.textContent = getString('enabled', 'Notificações ativadas');
                        });
                    } else if (permission === 'denied') {
                        triggers.forEach(function (el) {
                            el.setAttribute('disabled', '');
                            el.textContent = getString('blocked', 'Notificações bloqueadas');
                        });
                    }
                } catch (error) {
                    console.warn('[redemapas-push]', error);
                }
            });
        });
    }

    function init() {
        if (initialized) return;
        initialized = true;
        var config = getConfig();
        if (config && config.serviceWorkerUrl) {
            ensureServiceWorker(config).catch(function (e) { console.warn('[redemapas-push]', e); });
        }
        setupInstallTriggers();
        setupPushTriggers();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
