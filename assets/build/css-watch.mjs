import out from 'cli-output';
import watch from 'watch';
import css from './css.mjs';
import path from 'path';
import fs from 'fs-extra';

const appDir = process.cwd();
const tailwindConfig = `${appDir}/tailwind.config.js`;
const cssLocation = `${appDir}/assets/css`;
const templateLocation = `${appDir}/assets/templates`;
const additionalTemplatesLocation = `${appDir}/src`;

const extensions = [
    '.html',
    '.twig',
    '.css',
    '.pcss',
    '.svg',
];

const watchTreeOptions = {
    interval: 0.5,
};

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

    fs.watch(
        tailwindConfig,
        () => {
            clearTimeout(timer);

            timer = setTimeout(() => {
                css();
            }, 50);
        },
    );

    watch.watchTree(
        cssLocation,
        watchTreeOptions,
        responder,
    );

    watch.watchTree(
        templateLocation,
        watchTreeOptions,
        responder,
    );

    watch.watchTree(
        additionalTemplatesLocation,
        watchTreeOptions,
        responder,
    );
};
