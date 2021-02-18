import out from 'cli-output';
import watch from 'watch';
import stylelint from './stylelint.mjs';
import path from "path";
import css from "./css.mjs";

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;

const extensions = [
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
        stylelint();
    }, 50);
};

export default () => {
    stylelint();

    out.info('Watching CSS for stylelint changes...');

    watch.watchTree(
        cssLocation,
        {
            interval: 0.5,
        },
        responder,
    );
};
