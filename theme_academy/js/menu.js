/* global jQuery */
/* global document */

jQuery(function () {
    'use strict';

    document.addEventListener("touchstart", function () { }, false);
    jQuery(function () {
        console.log('menu.js loaded');
        jQuery('body').wrapInner('<div class="wsmenucontainer" />');
        jQuery('<div class="overlapblackbg"></div>').prependTo('.wsmenu');

        jQuery('#wsnavtoggle').click(function () {
            jQuery('body').toggleClass('wsactive');
        });

        jQuery('.overlapblackbg').click(function () {
            jQuery("body").removeClass('wsactive');
        });

        // Level 1: Standard and Mega Menu
        jQuery('.wsmenu > .wsmenu-list > li').each(function () {
            if (jQuery(this).has('.sub-menu').length || jQuery(this).has('.wsmegamenu').length) {
                jQuery(this).children('.wsmenu-click').remove(); // Prevent duplicates
                jQuery(this).prepend('<span class="wsmenu-click"><i class="wsmenu-arrow"></i></span>');
            }
        });

        // Level 1: Click Handler
        jQuery('.wsmenu-click').click(function () {
            // Identify current list item and its siblings
            var $currentLi = jQuery(this).closest('li');
            var $siblings = $currentLi.siblings();

            // === CLEANUP CURRENT ===
            // Remove .open class to prevent CSS !important override
            $currentLi.removeClass('open');

            // === SIBLING HANDLING (DEEP RESET) ===
            // 1. Hide immediate sibling submenus/megamenus
            $siblings.children('.sub-menu, .wsmegamenu').hide();

            // 2. Remove .open class from siblings
            $siblings.removeClass('open');

            // 3. DEEP CLEAN: Reset ALL nested state within siblings
            // This ensures that if you clicked "News", "Category" is completely scrubbed
            $siblings.find('.sub-menu').hide();
            $siblings.find('.wsmenu-arrow').removeClass('wsmenu-rotate');
            $siblings.find('.open').removeClass('open');

            // === CURRENT ITEM HANDLING ===
            // Toggle current arrow
            jQuery(this).children('.wsmenu-arrow').toggleClass('wsmenu-rotate');

            // Toggle current item with callback for Deep Reset on Close
            // Uses siblings() logic relative to the CLICK element (the arrow wrapper)
            jQuery(this).siblings('.sub-menu').slideToggle('slow', function () {
                // Callback: check if we just closed it
                if (!jQuery(this).is(":visible")) {
                    // We closed the parent. Now forcefully close all children to reset state.
                    jQuery(this).find('.sub-menu').hide();
                    jQuery(this).find('.wsmenu-arrow').removeClass('wsmenu-rotate');
                    jQuery(this).find('.open').removeClass('open');
                }
            });
            jQuery(this).siblings('.wsmegamenu').slideToggle('slow', function () {
                if (!jQuery(this).is(":visible")) {
                    // Reset megamenu state (though megamenus usually don't have nested submenus like this, it's safe)
                    jQuery(this).find('.sub-menu').hide();
                    jQuery(this).find('.wsmenu-arrow').removeClass('wsmenu-rotate');
                    jQuery(this).find('.open').removeClass('open');
                }
            });
        });

        // Level 2: Nested Submenus
        jQuery('.wsmenu > .wsmenu-list > li > ul > li').each(function () {
            if (jQuery(this).has('.sub-menu').length) {
                jQuery(this).children('.wsmenu-click02').remove();
                jQuery(this).prepend('<span class="wsmenu-click02"><i class="wsmenu-arrow"></i></span>');
            }
        });

        // Level 3: Nested Submenus
        jQuery('.wsmenu > .wsmenu-list > li > ul > li > ul > li').each(function () {
            if (jQuery(this).has('.sub-menu').length) {
                jQuery(this).children('.wsmenu-click03').remove();
                jQuery(this).prepend('<span class="wsmenu-click03"><i class="wsmenu-arrow"></i></span>');
            }
        });

        // Level 4: Nested Submenus
        jQuery('.wsmenu > .wsmenu-list > li > ul > li > ul > li > ul > li').each(function () {
            if (jQuery(this).has('.sub-menu').length) {
                jQuery(this).children('.wsmenu-click04').remove();
                jQuery(this).prepend('<span class="wsmenu-click04"><i class="wsmenu-arrow"></i></span>');
            }
        });

        // Move the click event handler inside the jQuery(function() { }) block
        // Level 2 Click Handler
        jQuery('.wsmenu-click02').click(function () {
            var $currentLi = jQuery(this).closest('li');
            var $siblings = $currentLi.siblings();

            // Clean state
            $currentLi.removeClass('open');
            $siblings.removeClass('open');

            // SIBLING DEEP RESET
            $siblings.children('.sub-menu').hide();
            $siblings.find('.sub-menu').hide();
            $siblings.find('.wsmenu-arrow').removeClass('wsmenu-rotate');
            $siblings.find('.open').removeClass('open');

            // CURRENT ITEM
            jQuery(this).children('.wsmenu-arrow').toggleClass('wsmenu-rotate');
            jQuery(this).siblings('.sub-menu').slideToggle('slow', function () {
                if (!jQuery(this).is(":visible")) {
                    jQuery(this).find('.sub-menu').hide();
                    jQuery(this).find('.wsmenu-arrow').removeClass('wsmenu-rotate');
                    jQuery(this).find('.open').removeClass('open');
                }
            });
        });

        // Level 3 Click Handler
        jQuery('.wsmenu-click03').click(function () {
            var $currentLi = jQuery(this).closest('li');
            var $siblings = $currentLi.siblings();

            // Clean state
            $currentLi.removeClass('open');
            $siblings.removeClass('open');

            // SIBLING DEEP RESET
            $siblings.children('.sub-menu').hide();
            $siblings.find('.sub-menu').hide();
            $siblings.find('.wsmenu-arrow').removeClass('wsmenu-rotate');
            $siblings.find('.open').removeClass('open');

            // CURRENT ITEM
            jQuery(this).children('.wsmenu-arrow').toggleClass('wsmenu-rotate');
            jQuery(this).siblings('.sub-menu').slideToggle('slow', function () {
                if (!jQuery(this).is(":visible")) {
                    jQuery(this).find('.sub-menu').hide();
                    jQuery(this).find('.wsmenu-arrow').removeClass('wsmenu-rotate');
                    jQuery(this).find('.open').removeClass('open');
                }
            });
        });

        // Level 4 Click Handler
        jQuery('.wsmenu-click04').click(function () {
            var $currentLi = jQuery(this).closest('li');
            var $siblings = $currentLi.siblings();

            // Clean state
            $currentLi.removeClass('open');
            $siblings.removeClass('open');

            // SIBLING DEEP RESET
            $siblings.children('.sub-menu').hide();
            $siblings.find('.sub-menu').hide();
            $siblings.find('.wsmenu-arrow').removeClass('wsmenu-rotate');
            $siblings.find('.open').removeClass('open');

            // CURRENT ITEM
            jQuery(this).children('.wsmenu-arrow').toggleClass('wsmenu-rotate');
            jQuery(this).siblings('.sub-menu').slideToggle('slow', function () {
                if (!jQuery(this).is(":visible")) {
                    jQuery(this).find('.sub-menu').hide();
                    jQuery(this).find('.wsmenu-arrow').removeClass('wsmenu-rotate');
                    jQuery(this).find('.open').removeClass('open');
                }
            });
        });

        jQuery(window).on('resize', function () {

            if (jQuery(window).outerWidth() < 992) {
                jQuery('.wsmenu').css('height', jQuery(this).height() + "px");
                jQuery('.wsmenucontainer').css('min-width', jQuery(this).width() + "px");
            } else {
                jQuery('.wsmenu').removeAttr("style");
                jQuery('.wsmenucontainer').removeAttr("style");
                jQuery('body').removeClass("wsactive");
                jQuery('.wsmenu > .wsmenu-list > li > .wsmegamenu, .wsmenu > .wsmenu-list > li > ul.sub-menu, .wsmenu > .wsmenu-list > li > ul.sub-menu > li > ul.sub-menu, .wsmenu > .wsmenu-list > li > ul.sub-menu > li > ul.sub-menu > li > ul.sub-menu').removeAttr("style");
                // jQuery('.wsmenu-click').removeClass("ws-activearrow_ayman");
                jQuery('.wsmenu-click02 > i').removeClass("wsmenu-rotate");
                jQuery('.wsmenu-click03 > i').removeClass("wsmenu-rotate");
                jQuery('.wsmenu-click04 > i').removeClass("wsmenu-rotate");
            }

        });

        jQuery(window).trigger('resize');

    });
}());

document.addEventListener('DOMContentLoaded', function () {
    function initMobileMenu() {
        if (window.innerWidth >= 992) return;
        const nav = document.querySelector('.wsmenu');
        if (!nav) return;

        // Helper function to recursively close all nested open submenus and hide all display styles
        function closeNestedMenus(element) {
            // Remove .open class from ALL nested items (at any level)
            const allNestedItems = element.querySelectorAll(':scope .sub-menu li.open');
            allNestedItems.forEach(function (item) {
                item.classList.remove('open');
            });
            // Explicitly hide ALL nested submenus
            const allNestedSubmenus = element.querySelectorAll(':scope .sub-menu');
            allNestedSubmenus.forEach(function (submenu) {
                submenu.style.display = 'none';
            });
        }

        // Delegate clicks on all menu items
        nav.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;

            const li = link.parentElement;
            if (!li) return;

            // Check if this item has a submenu or megamenu
            const hasSubmenu = li.querySelector(':scope > .sub-menu') || li.querySelector(':scope > .wsmegamenu');
            if (!hasSubmenu) return; // No submenu, allow normal navigation

            // Has a submenu - prevent default navigation
            e.preventDefault();
            e.stopPropagation();

            // Find the corresponding toggler arrow for this level
            // It could be any of the levels' toggle classes
            const toggler = li.querySelector(':scope > .wsmenu-click, :scope > .wsmenu-click02, :scope > .wsmenu-click03, :scope > .wsmenu-click04');

            if (toggler && window.jQuery) {
                // Trigger the jQuery handler to get all the benefits (accordion, arrow sync, class cleanup)
                jQuery(toggler).trigger('click');
            }
        });
    }

    initMobileMenu();

    // simple resize handler: reload to reinitialize desktop/mobile behavior
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            // Only reload if crossing the 992px breakpoint
            // This prevents infinite loops on mobile scroll (some browsers resize on scroll)
        }, 200);
    });
});