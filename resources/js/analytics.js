// Analitiche dei click, senza cookie: invia un beacon quando si apre un link
// esterno, una mail o un elemento marcato con data-track="nome".
const endpoint = document.querySelector('meta[name="analytics-endpoint"]')?.content;

const trackLink = (event) => {
    if (!endpoint || (event.type === 'auxclick' && event.button !== 1)) {
        return;
    }

    const link = event.target.closest?.('a[href]');

    if (!link) {
        return;
    }

    const url = new URL(link.href, window.location.href);
    const name = link.dataset.track ?? '';
    const isExternal = url.protocol === 'mailto:' || (url.protocol.startsWith('http') && url.host !== window.location.host);

    if (!name && !isExternal) {
        return;
    }

    const data = new FormData();
    data.append('name', name);
    data.append('url', url.href);
    data.append('path', window.location.pathname);

    if (link.dataset.trackLabel) {
        data.append('label', link.dataset.trackLabel);
    }

    navigator.sendBeacon?.(endpoint, data);
};

document.addEventListener('click', trackLink, { capture: true });
document.addEventListener('auxclick', trackLink, { capture: true });
