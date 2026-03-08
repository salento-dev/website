<div class="relative z-40 nav:hidden">

    <button type="button" x-on:click="showMobileMenu = true"
        class="grid h-full w-22 place-items-center overflow-hidden transition duration-300 ease-out hover:bg-border/30 focus:ring-0 focus:outline-none min-[370px]:w-25 2xs:w-30 nav:hidden nav:w-auto"
        aria-label="Open menu" aria-expanded="false" x-bind:aria-expanded="showMobileMenu">
        <div>Menu</div>
    </button>


    <div x-show="showMobileMenu" class="fixed inset-0 z-50 nav:hidden" role="dialog" aria-modal="true"
        aria-labelledby="mobile-menu-title" x-on:click.self="showMobileMenu = false" x-init="$nextTick(() => initMobileMenu($el, $refs, $watch, () => showMobileMenu = false))"
        style="display: none;">

        <div x-ref="overlay" x-on:click="showMobileMenu = false"
            class="bg-dots-pattern absolute inset-0 bg-surface-600/21 backdrop-blur-sm" aria-hidden="true">
        </div>


        <div class="pointer-events-none relative flex h-full flex-col px-5" x-on:click="showMobileMenu = false">
            <span id="mobile-menu-title" class="sr-only">
                Navigation menu
            </span>


            <div
                class="pointer-events-none absolute top-0 right-0 left-0 z-20 flex items-center justify-between px-5 py-4 text-sm">

                <div>
                </div>


                <div x-ref="closeBtn">
                    <button type="button" x-on:click="showMobileMenu = false"
                        class="group pointer-events-auto flex h-10 items-center justify-center rounded-md bg-surface pr-3.5 pl-3 text-stone-200 transition duration-300 ease-out hover:bg-cream-100 focus:outline-none"
                        aria-label="Close menu">
                        <x-heroicon-o-x-mark class="mr-1.25 size-3.5 shrink-0 transition duration-300 ease-out will-change-transform group-hover:rotate-90" />
                        <span>Close</span>
                        <span
                            class="grid grid-cols-[0fr] transition-[grid-template-columns] duration-300 ease-out group-hover:grid-cols-[1fr]">
                            <span class="overflow-hidden">
                                <span class="ml-1 opacity-0 transition duration-300 ease-out group-hover:opacity-100">
                                    menu
                                </span>
                            </span>
                        </span>
                    </button>
                </div>
            </div>


            <div class="pointer-events-none mt-auto flex justify-center pb-6">
                <nav x-ref="menuPanel" class="pointer-events-auto relative z-10 w-full rounded-xl bg-surface"
                    aria-label="Main navigation" x-on:click.stop="">

                    <div x-ref="dragHandle"
                        class="-mt-5 flex cursor-grab touch-none flex-col items-center pt-8 pb-8 select-none active:cursor-grabbing"
                        aria-label="Drag to close menu">
                        <div class="h-1.25 w-12 rounded-full bg-surface-400/40"></div>
                    </div>


                    <ul x-ref="navItems" class="flex flex-col items-stretch px-5 text-center text-lg">
                        <li>
                            <x-partials.mobile-navbar-item route="home" route-name="Home" />
                        </li>
                        <li>
                            <x-partials.mobile-navbar-item route="events" route-name="Events" />
                        </li>
                        <li>
                            <x-partials.mobile-navbar-item route="resources" route-name="Resources" />
                        </li>
                    </ul>


                    <div x-ref="plusDecoration" class="flex items-center gap-5 px-5 pt-3 pb-4">
                        <div data-plus-line="" class="h-px w-full bg-border"></div>
                        <div data-plus-symbol="" class="text-sm text-bone-500">
                            +
                        </div>
                        <div data-plus-line="" class="h-px w-full bg-border"></div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile Menu Animation
    window.initMobileMenu = function(el, refs, watch, closeMenu) {
        if (!gsap || !motion || !Draggable) return;

        const overlay = refs.overlay;
        const menuPanel = refs.menuPanel;
        const closeBtn = refs.closeBtn;
        const navItems = refs.navItems.children;
        const dragHandle = refs.dragHandle;
        const badges = refs.navItems.querySelectorAll('[data-badge]');
        const plusDecoration = refs.plusDecoration;
        const plusLines = plusDecoration.querySelectorAll('[data-plus-line]');
        const plusSymbol = plusDecoration.querySelector('[data-plus-symbol]');
        let draggableInstance = null;
        let isClosingViaDrag = false;

        watch('showMobileMenu', (isOpen) => {
            if (isOpen) {
                // Block body scroll
                document.body.style.overflow = 'hidden';

                const tl = gsap.timeline({
                    onComplete: () => {
                        draggableInstance = Draggable.create(menuPanel, {
                            type: 'y',
                            trigger: dragHandle,
                            bounds: {
                                minY: -20,
                                maxY: 300
                            },
                            inertia: true,
                            onPress: function() {
                                gsap.to(dragHandle.querySelector('div'), {
                                    width: 72,
                                    duration: 0.3,
                                    ease: 'back.out(1.7)',
                                });
                            },
                            onDrag: function() {
                                // Solo drag verso il basso
                                if (this.y < 0) {
                                    gsap.set(menuPanel, { y: 0 });
                                    return;
                                }

                                const maxDragForOpacity = 250;
                                const dragProgress = Math.min(this.y / maxDragForOpacity, 1);
                                const newOpacity = Math.max(0, 1 - dragProgress);
                                gsap.set(overlay, {
                                    opacity: newOpacity
                                });

                                const maxDrag = 200;
                                const fillProgress = Math.min(1, this.y / maxDrag);
                                const fillWidth = fillProgress * 100;

                                gsap.set(dragHandle.querySelector('div'), {
                                    background: `linear-gradient(to right, rgb(120 113 108) ${fillWidth}%, rgb(120 113 108 / 0.4) ${fillWidth}%)`,
                                });
                            },
                            onRelease: function() {
                                gsap.to(dragHandle.querySelector('div'), {
                                    width: 48,
                                    background: 'rgb(120 113 108 / 0.4)',
                                    duration: 0.3,
                                    ease: 'power2.out',
                                });
                            },
                            onDragEnd: function() {
                                const threshold = 80;

                                if (this.y >= threshold) {
                                    // Chiudi IMMEDIATAMENTE il menu
                                    isClosingViaDrag = true;
                                    closeMenu();
                                    
                                    // Reset dopo che Alpine ha nascosto tutto
                                    setTimeout(() => {
                                        gsap.set([menuPanel, overlay, closeBtn, navItems, plusSymbol, plusLines, dragHandle.querySelector('div')], {
                                            clearProps: 'all'
                                        });
                                        if (badges.length > 0) {
                                            gsap.set(badges, { clearProps: 'all' });
                                        }
                                        isClosingViaDrag = false;
                                    }, 50);
                                } else {
                                    // Ritorna alla posizione originale
                                    gsap.to(menuPanel, {
                                        y: 0,
                                        duration: 0.4,
                                        ease: 'back.out(1.7)',
                                    });
                                    gsap.to(overlay, {
                                        opacity: 1,
                                        duration: 0.3,
                                    });
                                }

                                gsap.to(dragHandle.querySelector('div'), {
                                    width: 48,
                                    background: 'rgb(120 113 108 / 0.4)',
                                    duration: 0.3,
                                    ease: 'power2.out',
                                });
                            },
                        })[0];
                    },
                });

                tl.fromTo(overlay, {
                    opacity: 0
                }, {
                    opacity: 1,
                    duration: 0.3,
                    ease: 'power2.out'
                });
                tl.fromTo(closeBtn, {
                    y: -20,
                    opacity: 0
                }, {
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    ease: 'back.out(2)'
                }, '<0.1');
                tl.fromTo(menuPanel, {
                    y: 100,
                    opacity: 0
                }, {
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    ease: 'back.out(1.7)'
                }, '<0.1');
                tl.fromTo(navItems, {
                    y: 30,
                    opacity: 0
                }, {
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: 'back.out(2)'
                }, '<0.2');

                if (badges.length > 0) {
                    tl.fromTo(badges, {
                        scale: 0,
                        x: -10,
                        opacity: 0
                    }, {
                        scale: 1,
                        x: 0,
                        opacity: 1,
                        duration: 0.4,
                        stagger: 0.1,
                        ease: 'back.out(1.5)'
                    }, '<0.5');
                }

                tl.fromTo(plusLines[0], {
                    scaleX: 0,
                    transformOrigin: 'right center'
                }, {
                    scaleX: 1,
                    duration: 0.5,
                    ease: 'power2.out'
                }, '<');
                tl.fromTo(plusLines[1], {
                    scaleX: 0,
                    transformOrigin: 'left center'
                }, {
                    scaleX: 1,
                    duration: 0.5,
                    ease: 'power2.out'
                }, '<');
                tl.fromTo(plusSymbol, {
                    scale: 0,
                    opacity: 0
                }, {
                    scale: 1,
                    opacity: 1,
                    duration: 0.4,
                    ease: 'back.out(2.5)'
                }, '<');
            } else {
                // Restore body scroll
                document.body.style.overflow = '';

                if (draggableInstance) {
                    draggableInstance.kill();
                    draggableInstance = null;
                }

                // Se stiamo chiudendo via drag, abbiamo già resettato tutto
                if (isClosingViaDrag) {
                    return;
                }

                const tl = gsap.timeline();
                tl.to(plusSymbol, {
                    scale: 0,
                    opacity: 0,
                    duration: 0.2,
                    ease: 'power2.in'
                });
                tl.to(plusLines, {
                    scaleX: 0,
                    duration: 0.25,
                    ease: 'power2.in'
                }, '<0.1');

                if (badges.length > 0) {
                    tl.to(badges, {
                        scale: 0,
                        opacity: 0,
                        duration: 0.2,
                        stagger: 0.05,
                        ease: 'power2.in'
                    }, '<');
                }

                tl.to(navItems, {
                    y: 20,
                    opacity: 0,
                    duration: 0.25,
                    stagger: 0.03,
                    ease: 'power2.in'
                }, '<');
                tl.to(menuPanel, {
                    y: 50,
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in'
                }, '<');
                tl.to(closeBtn, {
                    y: -15,
                    opacity: 0,
                    duration: 0.25,
                    ease: 'power2.in'
                }, '<');
                tl.to(overlay, {
                    opacity: 0,
                    duration: 0.2,
                    ease: 'power2.in'
                }, '<0.1');
            }
        });
    }

    // Badge Animation
    window.initBadgeAnimation = function(el, type) {
        if (!gsap || !motion) return;

        const keyframes = type === 'rotate' ? {
            y: [0, -5, 0],
            rotate: [0, -3, 3, 0]
        } : {
            y: [0, -5, 0]
        };

        const tween = gsap.to(el, {
            keyframes,
            duration: 1,
            repeat: -1,
            repeatDelay: 1,
            delay: 1.5,
            ease: 'bounce.inOut',
            paused: true,
        });

        motion.inView(el.closest('[data-badge]'), () => {
            tween.play();
            return () => tween.pause();
        });
    }

    // Shine Animation
    window.initShineAnimation = function(el, refs) {
        if (!gsap || !motion) return;

        const shine = refs.shine;

        const tween = gsap.fromTo(
            shine, {
                x: '-100%',
                opacity: 1
            }, {
                x: '200%',
                opacity: 0,
                duration: 1.2,
                ease: 'power2.inOut',
                repeat: -1,
                repeatDelay: 1,
                paused: true,
            }
        );

        motion.inView(el, () => {
            tween.play();
            return () => tween.pause();
        });
    }
</script>
