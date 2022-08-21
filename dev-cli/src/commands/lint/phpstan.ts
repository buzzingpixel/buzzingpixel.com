import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import { PhpLintingPaths } from '../../Constants';

export default class Phpstan extends Command {
    public static summary = 'Run PHPStan on all project files';

    public async run (): Promise<void> {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        try {
            execSync(
                `
                    cd ${rootDir};
                    php -d memory_limit=4G vendor/phpstan/phpstan/phpstan analyse ${PhpLintingPaths};
                `,
                { stdio: 'inherit' },
            );
        } catch (error) {
        }
    }
}
