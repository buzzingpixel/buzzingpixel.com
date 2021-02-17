import out from 'cli-output';
import watch from 'watch';
import css from './css.mjs';

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;
const templateLocation = `${appDir}/assets/templates`;

export default () => {
    out.info('Watching CSS for compilation changes...');

    watch.watchTree(
        cssLocation,
        {
            interval: 0.5,
        },
        (file) => {
            if (typeof file !== 'object') {
                out.info(`File changed: ${file}`);
            }

            css();
        },
    );

    watch.watchTree(
        templateLocation,
        {
            interval: 0.5,
        },
        (file) => {
            if (typeof file !== 'object') {
                out.info(`File changed: ${file}`);
            }

            css();
        },
    );
};
