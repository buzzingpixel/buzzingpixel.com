import out from 'cli-output';
import watch from 'watch';
import stylelint from './stylelint.mjs';

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;

export default () => {
    out.info('Watching CSS for stylelint changes...');

    watch.watchTree(
        cssLocation,
        {
            interval: 0.5,
        },
        (file) => {
            if (typeof file !== 'object') {
                out.info(`File changed: ${file}`);
            }

            stylelint();
        },
    );
};
