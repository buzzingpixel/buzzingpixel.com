import crypto from 'crypto';
import fs from 'fs-extra';
import hexRGBA from 'postcss-hexrgba';
import out from 'cli-output';
import path from 'path';
import postcss from 'postcss';
import postcssClean from 'postcss-clean';
import postcssPresetEnv from 'postcss-preset-env';
import purgecss from '@fullhuman/postcss-purgecss';
import recursive from 'recursive-readdir-sync';
import tailwindcss from 'tailwindcss';

const appDir = process.cwd();
const cssLocation = `${appDir}/assets/css`;
const cssOutputPath = `${appDir}/public/assets/css`;
const cssOutputFileName = 'style.min.css';
const startFile = `${appDir}/assets/css/start.pcss`;
const libDir = `${appDir}/assets/css/lib`;

export default (prod) => {
    const mediaQueries = [];
    const mediaQueryCss = {};
    const files = {};
    let cssString = '';

    // Let the user know what we're doing
    out.info('Compiling CSS...');

    // Add the reset file
    mediaQueries.push(0);
    mediaQueryCss[0] = String(fs.readFileSync(startFile));
    files[startFile] = startFile;

    // Iterate through lib items recursively (we want lib first so we can
    // potentially override in our own CSS)
    recursive(libDir).forEach((filePath) => {
        // If we've already processed the file, don't do it again
        if (files[filePath] !== undefined) {
            return;
        }

        // Add the file to the files object
        files[filePath] = filePath;

        // Add the CSS content to our mediaQueryCss array
        mediaQueryCss[0] += String(fs.readFileSync(filePath));
    });

    // Iterate through the directories recursively
    recursive(cssLocation).forEach((filePath) => {
        // If we've already processed the file, don't do it again
        if (files[filePath] !== undefined) {
            return;
        }

        // Add the file to the files object
        files[filePath] = filePath;

        // Filename extension
        const ext = path.extname(filePath);

        // If the file extension is not css or pcss, we should ignore it
        if (ext !== '.css' && ext !== '.pcss') {
            return;
        }

        // Determine if file name contains media query as filename.1400.ext
        const pathParts = filePath.split('.');
        const mediaQuery = parseInt(pathParts[pathParts.length - 2], 10) || 0;

        // Add the media query to the array if it doesn't already exist
        if (mediaQueryCss[mediaQuery] === undefined) {
            mediaQueries.push(mediaQuery);
            mediaQueryCss[mediaQuery] = '';
        }

        // Add the CSS content to our mediaQueryCss array
        mediaQueryCss[mediaQuery] += String(fs.readFileSync(filePath));
    });

    // Sort the media queries least to greatest
    const sortedMediaQueries = mediaQueries.sort((a, b) => a - b);

    // Iterate through the sorted media queries, grab the relevant CSS, wrap
    // it in the appropriate media query, and concat it to the cssString
    sortedMediaQueries.forEach((i) => {
        if (i > 0) {
            cssString += `\n@media (min-width: ${i}px) {\n`;
        }

        cssString += mediaQueryCss[i];

        if (i > 0) {
            cssString += '}\n';
        }
    });

    // Process the CSS through postcss with desired plugins
    postcss(
        [
            // Use tailwind
            tailwindcss,
            // Formerly autoprefixer
            postcssPresetEnv({
                features: {
                    'custom-properties': {
                        preserve: false,
                    },
                    /**
                     * Fixes a bug when using with tailwind.
                     * @see https://github.com/tailwindcss/tailwindcss/issues/1190
                     */
                    'focus-within-pseudo-class': false,
                },
            }),
            // Purge out CSS that's not being used by our markup
            purgecss({
                content: [
                    `${appDir}/assets/templates/**/*.html`,
                    `${appDir}/assets/templates/**/*.twig`,
                ],
                defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
                whitelistPatternsChildren: [
                    /js-hide/,
                ],
            }),
            // Allow us to use hex in RGBA
            hexRGBA,
            // Minifier
            postcssClean({
                level: 2,
            }),
        ],
    )
        .process(cssString, {
            from: undefined,
        })
        .then((result) => {
            // Start the output file with the path
            let cssOutputFile = cssOutputPath;

            // Create the relative file path for the manifest
            let manifestPath = cssOutputFileName;

            // If prod is requested, get a hash of the css content and insert
            // it into the file path for cache breaking
            if (prod === true) {
                // Get a hash of the css content
                const md5 = crypto.createHash('md5');
                const hash = `${md5.update(result.css).digest('hex')}`;

                // and insert it into the file path for cache breaking
                cssOutputFile += `/${hash}`;

                // Update the manifest path
                manifestPath = `${hash}/${manifestPath}`;
            }

            // Empty the path
            fs.emptyDirSync(cssOutputPath);

            // Make sure gitignore is in place
            fs.writeFileSync(
                `${cssOutputPath}/.gitignore`,
                '*\n!.gitignore\n',
            );

            // If the directory doesn't exist, create it
            if (!fs.existsSync(cssOutputFile)) {
                fs.mkdirSync(cssOutputFile, { recursive: true });
            }

            // Now add the file name to the output filename
            cssOutputFile += `/${cssOutputFileName}`;

            // Write the file to disk
            fs.writeFileSync(cssOutputFile, result.css);

            if (prod !== true) {
                fs.writeFileSync(
                    `${cssOutputPath}/dev-cache-break.txt`,
                    Date.now().toString(),
                );
            }

            fs.writeFileSync(
                `${cssOutputPath}/manifest.json`,
                JSON.stringify({
                    [cssOutputFileName]: manifestPath,
                }, null, 4),
            );

            // All done
            out.success('CSS compiled');
        })
        .catch((error) => {
            out.error('There was a PostCSS compile error');
            out.error(`Error: ${error.name}`);
            out.error(`Reason: ${error.reason}`);
            out.error(`Message: ${error.message}`);
            out.error('END PostCSS compile error');
        });
};
