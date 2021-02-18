import out from 'cli-output';
import watch from 'watch';
import * as eslint from './eslint.mjs';
import path from "path";

const appDir = process.cwd();
const jsLocation = `${appDir}/assets/js`;

const extensions = [
    '.js',
];

const responder = (file) => {
    if (typeof file === 'object') {
        return;
    }

    if (extensions.indexOf(path.extname(file)) < 0) {
        return;
    }

    out.info(`File changed: ${file}`);

    eslint.processSourceFile(file);

    out.success('JS file linted');
};

export default () => {
    out.info('Watching JS for eslint changes...');

    eslint.default();

    watch.watchTree(
        jsLocation,
        {
            interval: 0.5,
        },
        responder,
    );
};
