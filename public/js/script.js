/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  Liquid Glass Design System — Enhanced JavaScript           ║
 * ║  Smooth animations, glassmorphism transitions, ripple FX    ║
 * ║  Version: 2.0.0                                             ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

(function () {
    'use strict';

    // ══════════════════════════════════════════════════════════
    // ══ REDUCED MOTION & GLOBAL CONFIG ═══════════════════════
    // ══════════════════════════════════════════════════════════

    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var isReducedMotion = prefersReducedMotion.matches;

    // Listen for changes to reduced motion preference
    prefersReducedMotion.addEventListener('change', function (e) {
        isReducedMotion = e.matches;
    });

    /**
     * Helper: returns 0 if reduced motion is preferred, otherwise the given duration
     */
    function motionDuration(ms) {
        return isReducedMotion ? 0 : ms;
    }

    /**
     * Helper: debounce function for resize handlers
     */
    function debounce(fn, delay) {
        var timer = null;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }

    /**
     * Easing: easeOutExpo — fast start, slow end
     */
    function easeOutExpo(t) {
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
    }


    // ══════════════════════════════════════════════════════════
    // ══ DOM CONTENT LOADED ═══════════════════════════════════
    // ══════════════════════════════════════════════════════════

    document.addEventListener('DOMContentLoaded', function () {

        // ══════════════════════════════════════════════════
        // ══ 1. PAGE TRANSITION ANIMATION ═════════════════
        // ══════════════════════════════════════════════════

        var mainContent = document.getElementById('main-content');

        function animatePageEntrance() {
            if (!mainContent) return;

            if (isReducedMotion) {
                // Instant — no animation
                mainContent.style.opacity = '1';
                return;
            }

            // Fade in + slide up the main content area
            mainContent.style.opacity = '0';
            mainContent.style.transform = 'translateY(12px)';
            mainContent.style.transition = 'none';

            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    mainContent.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    mainContent.style.opacity = '1';
                    mainContent.style.transform = 'translateY(0)';
                });
            });

            // Stagger child sections for a premium cascade effect
            var sections = mainContent.querySelectorAll(':scope > *');
            sections.forEach(function (section, index) {
                section.style.opacity = '0';
                section.style.transform = 'translateY(10px)';
                section.style.transition = 'none';

                setTimeout(function () {
                    section.style.transition = 'opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, 80 + (index * 60));
            });
        }

        animatePageEntrance();


        // ══════════════════════════════════════════════════
        // ══ 2. IMPROVED CARD ENTRANCE ANIMATION ═════════
        // ══════════════════════════════════════════════════
        // Uses IntersectionObserver for viewport-based reveal
        // with staggered delays and CSS class toggling.

        var cardObserver = null;

        function initCardEntranceAnimations() {
            var cards = document.querySelectorAll('.stat-card, [class*="grid"] > [class*="bg-"][class*="rounded-xl"], .chart-card, .glass-card');

            if (!cards.length) return;

            if (isReducedMotion) {
                // Show all cards instantly
                cards.forEach(function (card) {
                    card.classList.add('animate-visible');
                    card.style.opacity = '1';
                    card.style.transform = 'none';
                });
                return;
            }

            // Prepare cards for animation
            cards.forEach(function (card) {
                card.classList.add('animate-on-scroll');
            });

            // IntersectionObserver for viewport detection
            if ('IntersectionObserver' in window) {
                var observedIndex = 0;

                cardObserver = new IntersectionObserver(function (entries) {
                    var visibleEntries = entries.filter(function (e) { return e.isIntersecting; });

                    visibleEntries.forEach(function (entry, idx) {
                        var card = entry.target;
                        var delay = idx * 60; // 0.06s stagger

                        setTimeout(function () {
                            card.classList.add('animate-visible');
                        }, delay);

                        cardObserver.unobserve(card);
                    });
                }, {
                    root: mainContent || null,
                    rootMargin: '0px 0px -40px 0px',
                    threshold: 0.1
                });

                cards.forEach(function (card) {
                    cardObserver.observe(card);
                });
            } else {
                // Fallback: animate all cards with stagger
                cards.forEach(function (card, index) {
                    setTimeout(function () {
                        card.classList.add('animate-visible');
                    }, 60 + (index * 60));
                });
            }
        }

        // Legacy function name preserved for backward compatibility
        function animateCards() {
            initCardEntranceAnimations();
        }

        animateCards();


        // ══════════════════════════════════════════════════
        // ══ 3. SMOOTH COUNTER ANIMATION ═════════════════
        // ══════════════════════════════════════════════════
        // Animates numeric values from 0 to target using
        // requestAnimationFrame with easeOutExpo easing.

        function animateCounter(element) {
            if (isReducedMotion) return; // Skip if reduced motion

            var rawText = element.textContent.trim();
            if (!rawText) return;

            // Parse prefix (Rp, $, etc.), suffix (%, dll), and numeric value
            var prefix = '';
            var suffix = '';
            var numericString = rawText;

            // Detect currency prefix (Rp, Rp., $, €)
            var prefixMatch = rawText.match(/^(Rp\.?\s*|[€$£¥])/);
            if (prefixMatch) {
                prefix = prefixMatch[0];
                numericString = rawText.substring(prefix.length);
            }

            // Detect suffix (%, unit, etc.)
            var suffixMatch = numericString.match(/(%|[a-zA-Z]+)$/);
            if (suffixMatch) {
                suffix = suffixMatch[0];
                numericString = numericString.substring(0, numericString.length - suffix.length);
            }

            // Clean up and parse the number
            numericString = numericString.replace(/\s/g, '').trim();

            // Handle both comma-as-decimal and dot-as-decimal formats
            // Indonesian uses dot as thousands separator and comma as decimal
            var hasDotThousands = /^\d{1,3}(\.\d{3})+([,]\d+)?$/.test(numericString);
            var cleanNumber;
            var decimalPlaces = 0;

            if (hasDotThousands) {
                // Indonesian format: 1.234.567,89
                var parts = numericString.split(',');
                cleanNumber = parseFloat(parts[0].replace(/\./g, '') + (parts[1] ? '.' + parts[1] : ''));
                decimalPlaces = parts[1] ? parts[1].length : 0;
            } else {
                // Standard format: 1,234,567.89
                var parts2 = numericString.split('.');
                cleanNumber = parseFloat(numericString.replace(/,/g, ''));
                decimalPlaces = parts2.length > 1 ? parts2[parts2.length - 1].length : 0;
            }

            if (isNaN(cleanNumber) || cleanNumber === 0) return;

            var targetValue = cleanNumber;
            var duration = 1500; // 1.5 seconds
            var startTime = null;
            var useIDFormat = hasDotThousands;

            /**
             * Format number to match original formatting style
             */
            function formatNumber(num) {
                var fixed = num.toFixed(decimalPlaces);

                if (useIDFormat) {
                    // Indonesian format with dot thousands separator
                    var numberParts = fixed.split('.');
                    var intPart = numberParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return decimalPlaces > 0 ? intPart + ',' + numberParts[1] : intPart;
                } else {
                    // Standard format with comma thousands separator
                    var numberParts2 = fixed.split('.');
                    var intPart2 = numberParts2[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    return decimalPlaces > 0 ? intPart2 + '.' + numberParts2[1] : intPart2;
                }
            }

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var elapsed = timestamp - startTime;
                var progress = Math.min(elapsed / duration, 1);
                var easedProgress = easeOutExpo(progress);

                var currentValue = easedProgress * targetValue;
                element.textContent = prefix + formatNumber(currentValue) + suffix;

                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    // Ensure final value is exact
                    element.textContent = rawText;
                }
            }

            // Set initial value to 0
            element.textContent = prefix + formatNumber(0) + suffix;
            requestAnimationFrame(step);
        }

        function initCounterAnimations() {
            var counters = document.querySelectorAll('.stat-value, .counter-animate');
            if (!counters.length || isReducedMotion) return;

            if ('IntersectionObserver' in window) {
                var counterObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            counterObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    root: mainContent || null,
                    threshold: 0.3
                });

                counters.forEach(function (el) {
                    counterObserver.observe(el);
                });
            } else {
                counters.forEach(function (el) {
                    animateCounter(el);
                });
            }
        }

        initCounterAnimations();


        // ══════════════════════════════════════════════════
        // ══ 4. ENHANCED GLASS MODAL TRANSITIONS ═════════
        // ══════════════════════════════════════════════════
        // Smooth scale + fade + blur entrance/exit for
        // modals using the `.modal-overlay.active` pattern.

        /**
         * Open a glass modal by ID
         * Adds 'active' class with smooth scale + fade + blur transition
         */
        window.openGlassModal = function (id) {
            var modal = document.getElementById(id);
            if (!modal) return;

            // Prepare modal for animation
            modal.style.display = '';

            if (isReducedMotion) {
                modal.classList.add('active');
                return;
            }

            // Force a reflow so the transition plays
            requestAnimationFrame(function () {
                modal.classList.add('active');
            });

            // Focus trap: focus first focusable element
            setTimeout(function () {
                var focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusable) focusable.focus();
            }, motionDuration(300));
        };

        /**
         * Close a glass modal by ID
         * Smooth scale down + fade out + blur transition
         */
        window.closeGlassModal = function (id) {
            var modal = document.getElementById(id);
            if (!modal) return;

            modal.classList.remove('active');

            if (isReducedMotion) {
                modal.style.display = 'none';
                return;
            }

            // Wait for CSS transition to finish before hiding
            setTimeout(function () {
                if (!modal.classList.contains('active')) {
                    modal.style.display = 'none';
                }
            }, motionDuration(350));
        };

        // Close modal on backdrop click
        document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) {
                    window.closeGlassModal(overlay.id);
                }
            });
        });

        // Close modal on Escape key (handled together with sidebar below)


        // ══════════════════════════════════════════════════
        // ══ 5. RIPPLE EFFECT ON BUTTONS ═════════════════
        // ══════════════════════════════════════════════════
        // Subtle light radial ripple on `.btn` click using
        // a temporary span element with CSS animation.

        function createRipple(event) {
            if (isReducedMotion) return;

            var button = event.currentTarget;
            var existingRipple = button.querySelector('.liquid-ripple');
            if (existingRipple) existingRipple.remove();

            var ripple = document.createElement('span');
            ripple.className = 'liquid-ripple';

            var rect = button.getBoundingClientRect();
            var size = Math.max(rect.width, rect.height) * 2;
            var x = event.clientX - rect.left - size / 2;
            var y = event.clientY - rect.top - size / 2;

            ripple.style.cssText =
                'position:absolute;' +
                'border-radius:50%;' +
                'width:' + size + 'px;' +
                'height:' + size + 'px;' +
                'left:' + x + 'px;' +
                'top:' + y + 'px;' +
                'background:radial-gradient(circle, rgba(255,255,255,0.35) 0%, rgba(255,255,255,0.1) 40%, transparent 70%);' +
                'transform:scale(0);' +
                'animation:liquidRippleExpand 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;' +
                'pointer-events:none;' +
                'z-index:1;';

            // Ensure button has relative positioning for the ripple
            var currentPosition = window.getComputedStyle(button).position;
            if (currentPosition === 'static') {
                button.style.position = 'relative';
            }
            button.style.overflow = 'hidden';

            button.appendChild(ripple);

            // Remove ripple element after animation
            setTimeout(function () {
                if (ripple.parentNode) ripple.parentNode.removeChild(ripple);
            }, 600);
        }

        // Inject ripple keyframe animation if not already present
        if (!document.getElementById('liquid-ripple-styles')) {
            var rippleStyle = document.createElement('style');
            rippleStyle.id = 'liquid-ripple-styles';
            rippleStyle.textContent =
                '@keyframes liquidRippleExpand {' +
                '  0% { transform: scale(0); opacity: 1; }' +
                '  100% { transform: scale(1); opacity: 0; }' +
                '}' +
                '.animate-on-scroll {' +
                '  opacity: 0;' +
                '  transform: translateY(20px) scale(0.97);' +
                '  transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);' +
                '}' +
                '.animate-visible {' +
                '  opacity: 1 !important;' +
                '  transform: translateY(0) scale(1) !important;' +
                '}';
            document.head.appendChild(rippleStyle);
        }

        // Attach ripple effect to all buttons
        document.querySelectorAll('.btn').forEach(function (btn) {
            btn.addEventListener('click', createRipple);
        });


        // ══════════════════════════════════════════════════
        // ══ 6. PARALLAX DECORATIVE BACKGROUND ═══════════
        // ══════════════════════════════════════════════════
        // Subtle mouse-driven parallax on decorative orbs.
        // Desktop only. Respects prefers-reduced-motion.

        function initParallaxBackground() {
            if (isReducedMotion) return;

            var isDesktop = window.matchMedia('(min-width: 1024px) and (hover: hover)');
            if (!isDesktop.matches) return;

            var orbs = document.querySelectorAll('.bg-orb, .decorative-orb, [class*="glass-orb"]');
            if (!orbs.length) return;

            var ticking = false;

            function handleMouseMove(e) {
                if (ticking) return;
                ticking = true;

                requestAnimationFrame(function () {
                    var centerX = window.innerWidth / 2;
                    var centerY = window.innerHeight / 2;

                    // Normalize mouse position to -1...1 range
                    var normalX = (e.clientX - centerX) / centerX;
                    var normalY = (e.clientY - centerY) / centerY;

                    orbs.forEach(function (orb, index) {
                        // Alternate direction and intensity per orb
                        var intensity = 1.5 + (index % 3); // 1.5px to 3.5px max
                        var direction = index % 2 === 0 ? 1 : -1;

                        var translateX = normalX * intensity * direction;
                        var translateY = normalY * intensity * direction;

                        orb.style.transform = 'translate(' + translateX.toFixed(2) + 'px, ' + translateY.toFixed(2) + 'px)';
                        orb.style.transition = 'transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
                    });

                    ticking = false;
                });
            }

            document.addEventListener('mousemove', handleMouseMove, { passive: true });

            // Cleanup on media query change (e.g., window resize to mobile)
            isDesktop.addEventListener('change', function (e) {
                if (!e.matches) {
                    document.removeEventListener('mousemove', handleMouseMove);
                    orbs.forEach(function (orb) {
                        orb.style.transform = '';
                        orb.style.transition = '';
                    });
                }
            });
        }

        initParallaxBackground();


        // ══════════════════════════════════════════════════
        // ══ 7. MOBILE SIDEBAR — Toggle, Backdrop, Swipe ═
        // ══════════════════════════════════════════════════

        var sidebar      = document.getElementById('app-sidebar');
        var backdrop     = document.getElementById('sidebar-backdrop');
        var btnHamburger = document.getElementById('btn-hamburger');
        var btnClose     = document.getElementById('btn-close-sidebar');
        var btnCollapse  = document.getElementById('btn-sidebar-collapse');
        var sidebarLinks = document.querySelectorAll('.sidebar-link');

        function setSidebarCollapsed(collapsed, persist) {
            if (!sidebar || window.innerWidth < 768) return;

            document.body.classList.toggle('sidebar-collapsed', collapsed);

            if (btnCollapse) {
                btnCollapse.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
                btnCollapse.setAttribute('aria-label', collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar');
                btnCollapse.setAttribute('title', collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar');
                var icon = btnCollapse.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-angles-left', !collapsed);
                    icon.classList.toggle('fa-angles-right', collapsed);
                }
            }

            if (persist) {
                try {
                    window.localStorage.setItem('fynca-sidebar-collapsed', collapsed ? '1' : '0');
                } catch (error) {
                    console.warn('Preferensi sidebar tidak dapat disimpan.', error);
                }
            }
        }

        if (window.innerWidth >= 768) {
            var storedSidebarState = window.localStorage.getItem('fynca-sidebar-collapsed');
            setSidebarCollapsed(storedSidebarState === '1', false);
        }

        if (btnCollapse) {
            btnCollapse.addEventListener('click', function () {
                setSidebarCollapsed(!document.body.classList.contains('sidebar-collapsed'), true);
            });
        }

        window.addEventListener('resize', debounce(function () {
            if (window.innerWidth < 768) {
                document.body.classList.remove('sidebar-collapsed');
            } else {
                setSidebarCollapsed(window.localStorage.getItem('fynca-sidebar-collapsed') === '1', false);
            }
        }, 150));

        function openSidebar() {
            if (!sidebar || !backdrop) return;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            backdrop.classList.remove('hidden', 'opacity-0');
            backdrop.classList.add('opacity-100');
            document.body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            if (!sidebar || !backdrop) return;
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            document.body.classList.remove('sidebar-open');
            // Wait for transition, then hide
            setTimeout(function () {
                backdrop.classList.add('hidden');
            }, 300);
        }

        // Expose globally for external usage
        window.openSidebar = openSidebar;
        window.closeSidebar = closeSidebar;

        // Hamburger button
        if (btnHamburger) {
            btnHamburger.addEventListener('click', function (e) {
                e.stopPropagation();
                openSidebar();
            });
        }

        // Close button
        if (btnClose) {
            btnClose.addEventListener('click', function () {
                closeSidebar();
            });
        }

        // Backdrop click
        if (backdrop) {
            backdrop.addEventListener('click', function () {
                closeSidebar();
            });
        }

        // Auto-close sidebar when a navigation link is clicked (mobile only)
        sidebarLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 768) {
                    closeSidebar();
                }
            });
        });

        // Close sidebar & modal on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSidebar();
                // Also close any open glass modals
                document.querySelectorAll('.modal-overlay.active').forEach(function (modal) {
                    window.closeGlassModal(modal.id);
                });
            }
        });

        // Smooth sidebar link hover scale effect
        sidebarLinks.forEach(function (link) {
            if (isReducedMotion) return;

            link.addEventListener('mouseenter', function () {
                this.style.transition = 'transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease';
                this.style.transform = 'scale(1.02) translateX(2px)';
            });
            link.addEventListener('mouseleave', function () {
                this.style.transform = 'scale(1) translateX(0)';
            });
        });


        // ══════════════════════════════════════════════════
        // ══ SWIPE GESTURES — Edge swipe to open/close ════
        // ══════════════════════════════════════════════════

        var touchStartX  = 0;
        var touchStartY  = 0;
        var touchCurrentX = 0;
        var isSwiping     = false;
        var swipeDirection = null; // 'open' or 'close'
        var EDGE_THRESHOLD = 30;  // px from left edge to start open swipe
        var SWIPE_MIN      = 60;  // min px to complete a swipe

        document.addEventListener('touchstart', function (e) {
            var touch = e.touches[0];
            touchStartX  = touch.clientX;
            touchStartY  = touch.clientY;
            touchCurrentX = touch.clientX;

            // Only allow open swipe from edge (when sidebar is closed)
            if (touchStartX <= EDGE_THRESHOLD && sidebar && sidebar.classList.contains('-translate-x-full')) {
                swipeDirection = 'open';
                isSwiping = true;
            }
            // Allow close swipe when sidebar is open
            else if (sidebar && sidebar.classList.contains('translate-x-0')) {
                swipeDirection = 'close';
                isSwiping = true;
            }
        }, { passive: true });

        document.addEventListener('touchmove', function (e) {
            if (!isSwiping) return;
            var touch = e.touches[0];
            touchCurrentX = touch.clientX;

            // Check if vertical scrolling (cancel swipe if mostly vertical)
            var deltaY = Math.abs(touch.clientY - touchStartY);
            var deltaX = Math.abs(touchCurrentX - touchStartX);
            if (deltaY > deltaX * 1.5) {
                isSwiping = false;
                return;
            }
        }, { passive: true });

        document.addEventListener('touchend', function () {
            if (!isSwiping) return;
            var deltaX = touchCurrentX - touchStartX;

            if (swipeDirection === 'open' && deltaX >= SWIPE_MIN) {
                openSidebar();
            } else if (swipeDirection === 'close' && deltaX <= -SWIPE_MIN) {
                closeSidebar();
            }

            isSwiping = false;
            swipeDirection = null;
        }, { passive: true });


        // ══════════════════════════════════════════════════
        // ══ SCROLL-TO-TOP BUTTON ═════════════════════════
        // ══════════════════════════════════════════════════

        var scrollTopBtn = document.getElementById('btn-scroll-top');

        if (scrollTopBtn && mainContent) {
            mainContent.addEventListener('scroll', function () {
                if (mainContent.scrollTop > 300) {
                    scrollTopBtn.classList.add('visible');
                } else {
                    scrollTopBtn.classList.remove('visible');
                }
            }, { passive: true });

            scrollTopBtn.addEventListener('click', function () {
                mainContent.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }


        // ══════════════════════════════════════════════════
        // ══ TABLE SCROLL HINTS ═══════════════════════════
        // ══════════════════════════════════════════════════

        function initTableScrollHints() {
            // Find all horizontal-scrollable table wrappers
            document.querySelectorAll('.overflow-x-auto').forEach(function (wrapper) {
                // Only on mobile/tablet
                if (window.innerWidth > 640) return;

                var table = wrapper.querySelector('table');
                if (!table) return;

                // Check if table overflows its wrapper
                if (table.scrollWidth > wrapper.clientWidth + 8) {
                    // Create hint element if not exists
                    if (!wrapper.querySelector('.table-scroll-hint')) {
                        var hint = document.createElement('div');
                        hint.className = 'table-scroll-hint';
                        hint.innerHTML = '<i class="fa-solid fa-arrows-left-right" style="margin-right:4px"></i> Geser';
                        wrapper.style.position = 'relative';
                        wrapper.appendChild(hint);
                        hint.style.display = 'block';

                        // Hide hint after first scroll
                        wrapper.addEventListener('scroll', function onScroll() {
                            hint.style.opacity = '0';
                            setTimeout(function () {
                                if (hint.parentNode) hint.parentNode.removeChild(hint);
                            }, 300);
                            wrapper.removeEventListener('scroll', onScroll);
                        });

                        // Auto-hide hint after 4 seconds
                        setTimeout(function () {
                            hint.style.transition = 'opacity 0.3s ease';
                            hint.style.opacity = '0';
                            setTimeout(function () {
                                if (hint.parentNode) hint.parentNode.removeChild(hint);
                            }, 300);
                        }, 4000);
                    }
                }
            });
        }

        // Run on load and debounce on resize
        initTableScrollHints();

        var handleResize = debounce(function () {
            initTableScrollHints();
            // Close sidebar if resizing to desktop
            if (window.innerWidth >= 768) {
                closeSidebar();
            }
        }, 250);

        window.addEventListener('resize', handleResize, { passive: true });


        // ══════════════════════════════════════════════════
        // ══ EXISTING FUNCTIONALITY (Preserved) ═══════════
        // ══════════════════════════════════════════════════

        // ── Konfirmasi hapus ──────────────────────────────────────
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var confirmed = confirm(
                    'Yakin ingin menghapus data ini?\n\nTindakan ini tidak dapat dibatalkan.'
                );
                if (confirmed) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });

        // ── Auto-hide flash messages setelah 4.5 detik ───────────
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity .4s ease, transform .4s ease';
                el.style.opacity    = '0';
                el.style.transform  = 'translateY(-4px)';
                setTimeout(function () {
                    if (el.parentNode) el.parentNode.removeChild(el);
                }, 420);
            }, 4500);
        });

        // ── Number input: tolak nilai negatif ────────────────────
        document.querySelectorAll('input[type="number"]').forEach(function (inp) {
            if (inp.getAttribute('min') === undefined) return;
            inp.addEventListener('input', function () {
                if (parseFloat(this.value) < 0) this.value = 0;
            });
        });


        // ══════════════════════════════════════════════════
        // ══ 9. CLEANUP — Observer disposal on navigate ══
        // ══════════════════════════════════════════════════

        window.addEventListener('beforeunload', function () {
            if (cardObserver) {
                cardObserver.disconnect();
                cardObserver = null;
            }
        });

    }); // END DOMContentLoaded

})(); // END IIFE
