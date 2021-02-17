import out from 'cli-output';
import stylelint from 'stylelint';

export default () => {
    const appDir = process.cwd();

    out.info('Linting CSS...');

    stylelint.lint({
        files: [
            `${appDir}/assets/css/*.css`,
            `${appDir}/assets/css/*.pcss`,
            `${appDir}/assets/css/*/**.css`,
            `${appDir}/assets/css/*/**.pcss`,
            `!${appDir}/assets/css/lib/*/**`,
            `!${appDir}/assets/css/Original.pcss`,
        ],
        formatter: 'string',
    })
        .then((resultObject) => {
            if (!resultObject.errored) {
                out.success('CSS has no lint');
            } else {
                out.error('There were a few linting errors');
            }

            // eslint-disable-next-line no-console
            console.log(resultObject.output);
        })
        .catch((err) => {
            // eslint-disable-next-line no-console
            console.error(err.stack);
        });
};
