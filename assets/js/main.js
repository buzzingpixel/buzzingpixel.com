/* eslint-disable no-new */

import Events from './Events.js';
import SetGlobalData from './SetUp/SetGlobalData.js';
import LoadAxios from './SetUp/LoadAxios.js';
import PrismCodeHighlighting from './Components/PrismCodeHighlighting.js';

// Setup
Events();
SetGlobalData();
LoadAxios();

// Components

// Load prism code highlighting
if (document.querySelector('code')
    || document.querySelector('pre')
) {
    new PrismCodeHighlighting();
}
