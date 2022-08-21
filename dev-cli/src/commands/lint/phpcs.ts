import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import { PhpLintingPaths } from '../../Constants';

export default class Phpcs extends Command {
    public static summary = 'Run PHPCS validation on all project files';

    public async run (): Promise<void> {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        try {
            execSync(
                `
                    cd ${rootDir};
                    vendor/bin/phpcs --config-set installed_paths ../../doctrine/coding-standard/lib,../../slevomat/coding-standard;
                    vendor/bin/phpcs ${PhpLintingPaths};
                `,
                { stdio: 'inherit' },
            );
        } catch (error) {
        }
    }
}
