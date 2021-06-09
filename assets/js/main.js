/* eslint-disable no-new */

/**
 * Imports
 */

// Setup
import Events from './Events.js';
import FileUploadField from './Components/FileUploadField.js';
import Flatpickr from './Components/Flatpickr.js';
import LoadAxios from './SetUp/LoadAxios.js';
import SetGlobalData from './SetUp/SetGlobalData.js';

// Components
import Cart from './Components/Cart.js';
import CartPayNow from './Components/CartPayNow.js';
import MainNav from './Components/MainNav.js';
import PrismCodeHighlighting from './Components/PrismCodeHighlighting.js';
import Selects from './Components/Selects.js';

window.Methods.FileUploadField = FileUploadField;

/**
 * Run
 */

// Setup
Events();
LoadAxios();
SetGlobalData();

// Components

// Main nav
window.Methods.MainNav = MainNav;

// Cart
window.Methods.Cart = Cart;

// Cart Pay Now
const payNow = document.querySelector('[ref="cartPayNow"]');
CartPayNow(payNow);

// Load prism code highlighting
if (document.querySelector('code')
    || document.querySelector('pre')
) {
    new PrismCodeHighlighting();
}

// Selects
const selectEls = document.querySelectorAll('[ref="select"]');
if (selectEls.length > 0) {
    new Selects(selectEls);
}

// Flatpickr
const flatpickrEls = document.querySelectorAll(
    'input[type="date"], input[type="datetime-local"]',
);
if (flatpickrEls.length > 0) {
    new Flatpickr(flatpickrEls);
}
