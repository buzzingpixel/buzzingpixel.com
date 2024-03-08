import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import chalk from "chalk";
import * as fs from 'fs-extra';
import Listr from "listr";

export default class DbRestore extends Command {
    // Allow variable arguments
    public static strict = false;

    public static summary = 'Restore database from file in backup directory';

    public static args = [
        {
            name: 'file',
            description: 'File name to restore',
            default: null,
        },
    ];

    public async run (): Promise<void> {
        const { args } = await this.parse(DbRestore);

        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        const backupDir = `${rootDir}/docker/localStorage/dbBackups`;

        if (! args.file) {
            fs.mkdirpSync(backupDir);

            const files = fs.readdirSync(backupDir);

            this.log(
                chalk.cyan('Provide a filename argument. Available files:'),
            );

            files.forEach((file) => {
                this.log(`  ${chalk.cyan(file)}`);
            });

            return;
        }

        const filePath = `${backupDir}/${args.file}`;

        const fileExists = fs.existsSync(filePath);

        if (!fileExists) {
            this.log(chalk.red('The specified file does not exist'));

            return;
        }

        const tasks = new Listr([{
            title: `Restoring database from ${filePath}`,
            task: () => {
                execSync(
                    `
                        docker cp ${filePath} buzzingpixel-db:/dump.psql;
                        docker exec -it buzzingpixel-db bash -c 'PGPASSWORD="\${DB_PASSWORD}"; pg_restore --clean -U "\${DB_USER}" -d "\${DB_DATABASE}" -v < "/dump.psql"';
                        docker exec -it buzzingpixel-db bash -c "rm /dump.psql";
                    `,
                    { stdio: 'inherit' },
                );
            },
        }]);

        await tasks.run();
    }
}
