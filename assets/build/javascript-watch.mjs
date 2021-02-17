import out from 'cli-output';
import watch from 'watch';
import * as javascript from './javascript.mjs';

const appDir = process.cwd();
const jsLocation = `${appDir}/assets/js`;

export default () => {
    out.info('Watching JS for compilation changes...');

    watch.watchTree(
        jsLocation,
        {
            interval: 0.5,
        },
        (file) => {
            if (typeof file !== 'object') {
                out.info(`File changed: ${file}`);

                javascript.processSourceFile(file);

                out.success('JS file compiled');

                return;
            }

            javascript.default();
        },
    );
};
