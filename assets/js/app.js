import "../../node_modules/jquery/dist/jquery.min";
import "../../node_modules/popper.js/dist/popper";
import "../../node_modules/bootstrap/dist/js/bootstrap";
import "./holder.min";

/**
 * @author Alberto Manuel Ochoa Fabre <maochoa31415@gmail.com>
 * @package Cine Cubano Online
 * @description Bootstrap theme for Cine Cubano Online
 */

const NAVBAR_COLLAPSE = '#cco-navbar-header';
const CCO_USERNAME_INPUT = '#cco-username';

$(function () {
    'use strict';

    $('[data-toggle="tooltip"]').tooltip();

    /**
     * @description Small plugin to add a scrollto behavior
     * @author Alberto Manuel Ochoa Fabre <maochoa31415@gmail.com>
     * @example
     * ```html
     *  
     * ```
     */
    $('[data-scrollto]').click(function (event) {
        event.preventDefault();
        // Store hash
        let elTarget = $(this).data('scrollto');

        $('html, body').animate({
            scrollTop: $(elTarget).offset().top - 60
        }, 1000);
    })

    // Set focus to usename when collapse is showed
    $(NAVBAR_COLLAPSE).on('show.bs.collapse', function () {
        // Wait for transition end
        setTimeout(() => {
            $(CCO_USERNAME_INPUT).focus();
        }, 500);
    });

    // Hide navbar content, simulate a 'Clickout listener'
    const hideNavCollapse = [
        'body section',
        'body footer',
        'body main',
        'body section'
    ]
    $(hideNavCollapse.join(',')).click(function () {
        $(NAVBAR_COLLAPSE).collapse('hide');
    });

    // Go to contact form from header
    $('#email-me').click(function () {
        $(NAVBAR_COLLAPSE).collapse('hide');

        // Delay to wait for collapse animation
        setTimeout(function () {
            $('#cco-getintouch-name').focus();
        }, 1300);
    });

    $('#cco-theme-selector').click(function (event) {
        event.preventDefault();

        $('body').toggleClass('dark-theme');
    })

    // Open and close the search form
    $('#cco-open-search, #cco-close-search').click(function (event) {
        event.preventDefault();

        $('.cco-navbar-search').val('');
        $('.cco-navbar-search').toggleClass('open');

        // Wait for transition and set the focus
        setTimeout(() => {
            $('.cco-navbar-search.open .cco-search').focus();
        }, 800);
    })
})
