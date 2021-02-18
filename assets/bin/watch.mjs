import browsersync from '../build/browser-sync.mjs';
import cssWatch from '../build/css-watch.mjs';
import eslintWatch from '../build/eslint-watch.mjs';
import javascriptWatch from '../build/javascript-watch.mjs';
import stylelintWatch from '../build/stylelint-watch.mjs';

cssWatch();
stylelintWatch();
javascriptWatch();
eslintWatch();

if (process.argv[2] === 'browsersync') {
    browsersync();
}
