import Loader from '../Helpers/Loader.js';

class PrismCodeHighlighting {
    constructor () {
        // noinspection TypeScriptUMDGlobal
        Loader.loadCss('/assets/lib/prism/prism.min.css');
        Loader.loadCss('/assets/lib/prism/lang.customee.min.css');
        Loader.loadJs('/assets/lib/prism/prism.min.js').then(() => {
            Loader.loadJs('/assets/lib/prism/lang.ee.min.js').then(() => {
                window.Prism.highlightAll();
            });
        });
    }
}

export default PrismCodeHighlighting;
