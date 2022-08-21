import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import Listr from 'listr';

export default class DbBackup extends Command {
    public static summary = 'Backup the local database';

    public async run (): Promise<void> {
        const date = new Date();
        const y = date.getFullYear();
        const m = (date.getMonth() + 1).toString().padStart(2, '0');
        const d = date.getDate();
        const h = date.getHours();
        const i = date.getMinutes();
        const s = date.getSeconds();

        let dateStr = `${y}-${m}-${d}__${h}-${i}-${s}`;

        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        const tasks = new Listr([{
            title: `Backing up database to docker/localStorage/dbBackups/${dateStr}.psql`,
            task: async () => {
                await new Promise(r => setTimeout(r, 500));

                fs.mkdirpSync(
                    `${rootDir}/docker/localStorage/dbBackups`
                );

                execSync(
                    "docker exec buzzingpixel-db bash -c 'pg_dump --dbname=postgresql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:5432/${DB_DATABASE} -w -Fc > /dump.psql';",
                    { stdio: 'inherit' },
                );

                execSync(
                    `
                docker cp buzzingpixel-db:/dump.psql ${rootDir}/docker/localStorage/dbBackups/${dateStr}.psql;
            `,
                    { stdio: 'inherit' },
                );

                execSync(
                    'docker exec ${interactiveArgs} buzzingpixel-db bash -c "rm /dump.psql";',
                    { stdio: 'inherit' },
                );
            }
        }]);

        await tasks.run();
    }
}
