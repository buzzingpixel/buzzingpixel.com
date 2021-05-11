import browsersync from 'browser-sync';

const bs = browsersync.create();

const appDir = process.cwd();
const cssCacheBreakFile = `${appDir}/public/assets/css//**`;
const jsOutputDir = `${appDir}/public/assets/js//**`;
const srcDir = `${appDir}/src//**`;
const configDir = `${appDir}/config//**`;

const watchFiles = [
    cssCacheBreakFile,
    jsOutputDir,
    srcDir,
    configDir,
    '!*.diff',
    '!*.err',
    '!*.log',
    '!*.orig',
    '!*.rej',
    '!*.swo',
    '!*.swp',
    '!*.vi',
    '!*.cache',
    '!*.DS_Store',
    '!*.tmp',
    '!*error_log',
    '!*Thumbs.db',
];

export default () => {
    bs.init({
        files: watchFiles,
        ghostMode: false,
        injectChanges: false,
        notify: false,
        proxy: 'https://buzzingpixel.localtest.me/',
        https: {
            key: `${appDir}/../traefik-dev/certs/localhost.key`,
            cert: `${appDir}/../traefik-dev/certs/localhost.cert`
        },
        reloadDelay: 100,
        reloadDebounce: 100,
        reloadThrottle: 1000,
        watchOptions: {
            ignored: '.DS_Store',
            usePolling: false,
        },
    });
};
