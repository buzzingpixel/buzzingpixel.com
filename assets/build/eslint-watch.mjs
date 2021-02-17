import out from 'cli-output';
import watch from 'watch';
import * as eslint from './eslint.mjs';

const appDir = process.cwd();
const jsLocation = `${appDir}/assets/js`;

export default () => {
    out.info('Watching JS for eslint changes...');

    watch.watchTree(
        jsLocation,
        {
            interval: 0.5,
        },
        (file) => {
            if (typeof file !== 'object') {
                out.info(`File changed: ${file}`);

                eslint.processSourceFile(file);

                out.success('JS file linted');

                return;
            }

            eslint.default();
        },
    );
};
