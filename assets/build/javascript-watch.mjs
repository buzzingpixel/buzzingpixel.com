import out from 'cli-output';
import watch from 'watch';
import * as javascript from './javascript.mjs';
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

    javascript.processSourceFile(file);

    out.success('JS file compiled');
};

export default () => {
    out.info('Watching JS for compilation changes...');

    javascript.default();

    watch.watchTree(
        jsLocation,
        {
            interval: 0.5,
        },
        responder,
    );
};
