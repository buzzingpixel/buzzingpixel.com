/**
 * @see https://joshuajohnson.co.uk/Choices/
 * @see https://github.com/jshjohnson/Choices
 */

import Loader from '../Helpers/Loader.js';

class Selects {
    /**
     * @param {NodeList} els
     */
    constructor (els) {
        Loader.loadCss(
            'https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css',
        );

        Loader.loadJs('https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js').then(() => {
            els.forEach((el) => {
                // eslint-disable-next-line no-undef,no-new
                new Choices(el, {
                    removeItemButton: true,
                    shouldSort: false,
                    classNames: {
                        containerInner: 'inline-block align-top p-2 border overflow-hidden block w-full shadow-sm focus:ring-meteor focus:border-meteor sm:text-sm border-gray-300 rounded-md',
                    },
                });
            });
        });
    }
}

export default Selects;
