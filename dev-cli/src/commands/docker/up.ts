import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import chalk from 'chalk';

export default class Up extends Command {
    public static summary = 'Start Docker environment';

    public async run (): Promise<void> {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        this.log(chalk.cyan('Starting the Docker environment…'));

        execSync(
            `
                cd ${rootDir};
                chmod -R 0777 storage;
                docker compose -f docker-compose.dev.yml -p buzzingpixel up -d;
                docker exec -it buzzingpixel-app bash -c "chmod -R 0777 /var/www/storage";
            `,
            { stdio: 'inherit' },
        );

        this.log(chalk.green('Docker environment is online.'));
    }
}
