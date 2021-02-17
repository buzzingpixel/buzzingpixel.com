import eslint from 'eslint';
import fs from 'fs-extra';
import path from 'path';
import recursive from 'recursive-readdir-sync';
import out from 'cli-output';

const appDir = process.cwd();
const jsLocation = `${appDir}/assets/js`;
const replacer = appDir + path.sep;

export function processSourceFile (filePath) {
    const ext = path.extname(filePath);

    // If the file extension is not js, we should ignore it
    if (ext !== '.js') {
        return;
    }

    // Get the ESLint CLIEngine. This handles getting our eslint configs
    const { CLIEngine } = eslint;
    const cli = new CLIEngine();

    // Get the relative filename path
    const fileNamePath = filePath.slice(
        filePath.indexOf(replacer) + replacer.length,
    );

    // Run
    const report = cli.executeOnText(
        String(fs.readFileSync(filePath)),
        fileNamePath,
        true,
    );

    // If there are no errors we can stop
    if (report.errorCount < 1) {
        return;
    }

    // Display errors
    const formatter = cli.getFormatter();
    // eslint-disable-next-line no-console
    console.log(formatter(report.results));
}

export default () => {
    // Let the user know what we're doing
    out.info('Linting JS...');

    // Recursively iterate through JS
    recursive(jsLocation).forEach((filePath) => {
        processSourceFile(filePath);
    });

    out.success('JS linting finished');
};
