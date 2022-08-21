import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import * as fs from 'fs-extra';
import Down from '../docker/down';
import Up from '../docker/up';
import App from '../container/app';
import Listr from "listr";

export default class Sync extends Command {
    public static summary = `Syncs production database and content to local environment`;

    public async run (): Promise<void> {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);

        const tasks = new Listr([
            {
                title: 'Set permissions on scripts',
                task: async () => {
                    execSync(
                        `
                            cd ${rootDir};
                            chmod +x docker/scripts/dev/ensure-ssh-keys-working.sh;
                            chmod +x docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;
                            chmod +x docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;
                            chmod +x docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;
                            chmod +x docker/scripts/dev/sync-to-local-04-db-restore-data.sh;
                            chmod +x docker/scripts/dev/sync-to-local-05-rsync.sh;
                        `,
                        { stdio: 'inherit' },
                    );
                }
            },
            {
                title: 'Ensure environment is down',
                task: async () => {
                    const DownC = new Down([], this.config);
                    await DownC.run();
                }
            },
            {
                title: 'Remove the database volume',
                task: async () => {
                    execSync(
                        'docker volume rm buzzingpixel_db-volume;',
                        { stdio: 'inherit' },
                    );
                }
            },
            {
                title: 'Bring the environment online',
                task: async () => {
                    const UpC = new Up([], this.config);
                    await UpC.run();
                }
            },
            {
                title: 'Set up the database',
                task: async () => {
                    const AppC = new App([
                        'php',
                        'cli',
                        'database:setup',
                    ], this.config);
                    await AppC.run();
                }
            },
            {
                title: 'Run sync scripts',
                task: async () => {
                    execSync(
                        `
                            cd ${rootDir};
                            docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh up -d;
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;";
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;";
                            docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;";
                            docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-04-db-restore-data.sh;";
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-05-rsync.sh;";
                            docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh down;
                        `,
                        { stdio: 'inherit' },
                    );
                }
            },
        ]);

        tasks.run();
    }
}
