/* eslint-disable no-new */

/**
 * Imports
 */

// Setup
import Events from './Events.js';
import LoadAxios from './SetUp/LoadAxios.js';
import SetGlobalData from './SetUp/SetGlobalData.js';

// Components
import MainNav from './Components/MainNav.js';
import PrismCodeHighlighting from './Components/PrismCodeHighlighting.js';
import Selects from './Components/Selects.js';

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
