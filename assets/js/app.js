// require jQuery normally
//import $ from "../../node_modules/jquery/dist/jquery.min";
const $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;

import "../../node_modules/popper.js/dist/popper";
import "../../node_modules/bootstrap/dist/js/bootstrap";
import "./holder.min";

/**
 * @author Alberto Manuel Ochoa Fabre <maochoa31415@gmail.com>
 * @package Cine Cubano Online
 * @description Bootstrap theme for Cine Cubano Online
 */

const CCO_USERNAME_INPUT = '#cco-username';

$(function () {
    'use strict';

    // Init
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // Go to contact form from header
    // $('#email-me').click(function () {
    //     $('#cco-getintouch-name').focus();
    // });

    $('#cco-theme-selector').click(function (event) {
        event.preventDefault();

        $('body').toggleClass('dark-theme');
    });

    // Show or hide the password value
    $('.cco-password-action').click(function () {
        const self = $(this);
        const parent = self.parent();
        const inputPass = parent.children('input');
        const inputPassAttr = inputPass.attr('type');

        inputPass.attr('type', inputPassAttr === 'password' ? 'text' : 'password');
        self.attr('title', inputPassAttr === 'password' ? 'Hide password' : 'Show password');

        self.children().toggleClass('mdi-eye');
        self.children().toggleClass('mdi-eye-off');
    });

    
});
