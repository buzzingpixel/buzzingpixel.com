import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import chalk from 'chalk';

export default class Down extends Command {
    public static summary = 'Stop Docker environment';

    public async run (): Promise<void> {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        this.log(chalk.cyan('Stopping the Docker environment…'));

        execSync(
            `
                cd ${rootDir};
                docker compose -f docker-compose.yml -f docker-compose.dev.yml -p buzzingpixel down;
            `,
            { stdio: 'inherit' },
        );

        this.log(chalk.green('Docker environment is offline.'));
    }
}
