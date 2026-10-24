import './bootstrap';
import './analytics';
import autoAnimate from '@formkit/auto-animate';
import { gsap } from 'gsap';
import { Draggable } from 'gsap/Draggable';
import { SplitText } from 'gsap/SplitText';
import { inView } from '@motionone/dom';

// Registra i plugin GSAP
gsap.registerPlugin(Draggable, SplitText);

// Crea una funzione hover helper come Filament
const hover = (element, onEnter) => {
    let cleanup;
    
    element.addEventListener('mouseenter', () => {
        cleanup = onEnter();
    });
    
    element.addEventListener('mouseleave', () => {
        if (cleanup) cleanup();
    });
};

// Funzione per creare un loop orizzontale infinito con GSAP
const horizontalLoop = (items, config) => {
    items = gsap.utils.toArray(items);
    config = config || {};
    let tl = gsap.timeline({
        repeat: config.repeat,
        paused: config.paused,
        defaults: { ease: 'none' },
        onReverseComplete: () => tl.totalTime(tl.rawTime() + tl.duration() * 100)
    });
    let length = items.length;
    let startX = items[0].offsetLeft;
    let times = [];
    let widths = [];
    let xPercents = [];
    let curIndex = 0;
    let pixelsPerSecond = (config.speed || 1) * 100;
    let snap = config.snap === false ? v => v : gsap.utils.snap(config.snap || 1);
    let totalWidth;
    let curX;
    let distanceToStart;
    let distanceToLoop;
    let item;
    let i;
    
    gsap.set(items, {
        xPercent: (i, el) => {
            let w = widths[i] = parseFloat(gsap.getProperty(el, 'width', 'px'));
            xPercents[i] = snap(parseFloat(gsap.getProperty(el, 'x', 'px')) / w * 100 + gsap.getProperty(el, 'xPercent'));
            return xPercents[i];
        }
    });
    
    gsap.set(items, { x: 0 });
    
    totalWidth = items[length - 1].offsetLeft + xPercents[length - 1] / 100 * widths[length - 1] - startX + items[length - 1].offsetWidth * gsap.getProperty(items[length - 1], 'scaleX') + (parseFloat(config.paddingRight) || 0);
    
    for (i = 0; i < length; i++) {
        item = items[i];
        curX = xPercents[i] / 100 * widths[i];
        distanceToStart = item.offsetLeft + curX - startX;
        distanceToLoop = distanceToStart + widths[i] * gsap.getProperty(item, 'scaleX');
        tl.to(item, { xPercent: snap((curX - distanceToLoop) / widths[i] * 100), duration: distanceToLoop / pixelsPerSecond }, 0)
          .fromTo(item, { xPercent: snap((curX - distanceToLoop + totalWidth) / widths[i] * 100) }, { xPercent: xPercents[i], duration: (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond, immediateRender: false }, distanceToLoop / pixelsPerSecond)
          .add('label' + i, distanceToStart / pixelsPerSecond);
        times[i] = distanceToStart / pixelsPerSecond;
    }
    
    return tl;
};

// Inizializza array globale per le animazioni
window.__heroAnimations = [];

// Rendi disponibili globalmente le utilità
window.gsap = gsap;
window.Draggable = Draggable;
window.SplitText = SplitText;
window.motion = { inView, hover };
window.FilamentAnimations = { horizontalLoop };
window.autoAnimate = autoAnimate;

// Rispetta la preferenza di sistema "riduci movimento"
const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
window.prefersReducedMotion = () => reducedMotionQuery.matches;
