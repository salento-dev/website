import { domToBlob } from 'modern-screenshot';

/**
 * Shrink single-line texts marked with [data-fit] until they fit the cover width.
 */
const fitTexts = () => {
    document.querySelectorAll('[data-fit]').forEach((element) => {
        let size = parseFloat(getComputedStyle(element).fontSize);

        while (element.scrollWidth > element.clientWidth && size > 12) {
            size -= 2;
            element.style.fontSize = `${size}px`;
        }
    });
};

const ready = document.fonts.ready.then(fitTexts);

/**
 * Export the cover as a PNG Blob at its native size. Called by the admin page that embeds this one in an iframe.
 */
window.captureCover = async () => {
    await ready;

    const cover = document.querySelector('[data-cover]');

    return domToBlob(cover, {
        width: cover.offsetWidth,
        height: cover.offsetHeight,
        scale: 1,
        type: 'image/png',
    });
};
