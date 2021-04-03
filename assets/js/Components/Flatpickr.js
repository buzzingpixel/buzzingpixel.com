/**
 * @see https://flatpickr.js.org/
 */

import Loader from '../Helpers/Loader.js';

class Flatpickr {
    /**
     * @param {NodeList} els
     */
    constructor (els) {
        Loader.loadCss(
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        );

        Loader.loadJs('https://cdn.jsdelivr.net/npm/flatpickr').then(() => {
            els.forEach((el) => {
                const value = el.getAttribute('value');

                let options = {
                    enableTime: true,
                    dateFormat: 'Y-m-d h:i K',
                };

                if (el.getAttribute('type') === 'date') {
                    options = {
                        enableTime: false,
                        dateFormat: 'Y-m-d',
                    };
                }

                if (value) {
                    options.defaultDate = value;
                }

                window.flatpickr(el, options);
            });
        });
    }
}

export default Flatpickr;
