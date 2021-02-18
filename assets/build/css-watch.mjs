import out from 'cli-output';
import watch from 'watch';
import css from './css.mjs';
import path from 'path';

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;
const templateLocation = `${appDir}/assets/templates`;
const additionalTemplatesLocation = `${appDir}/src`;

const extensions = [
    '.html',
    '.twig',
    '.css',
    '.pcss',
];

let timer = setTimeout(() => {});

const responder = (file) => {
    if (typeof file === 'object') {
        return;
    }

    if (extensions.indexOf(path.extname(file)) < 0) {
        return;
    }

    out.info(`File changed: ${file}`);

    clearTimeout(timer);

    timer = setTimeout(() => {
        css();
    }, 50);
};

export default () => {
    out.info('Watching CSS for compilation changes...');

    css();

    watch.watchTree(
        cssLocation,
        {
            interval: 0.5,
        },
        responder,
    );

    watch.watchTree(
        templateLocation,
        {
            interval: 0.5,
        },
        responder,
    );

    watch.watchTree(
        additionalTemplatesLocation,
        {
            interval: 0.5,
        },
        responder,
    );
};
