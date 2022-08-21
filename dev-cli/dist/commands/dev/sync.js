"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const down_1 = (0, tslib_1.__importDefault)(require("../docker/down"));
const up_1 = (0, tslib_1.__importDefault)(require("../docker/up"));
const app_1 = (0, tslib_1.__importDefault)(require("../container/app"));
const listr_1 = (0, tslib_1.__importDefault)(require("listr"));
class Sync extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        const tasks = new listr_1.default([
            {
                title: 'Set permissions on scripts',
                task: async () => {
                    (0, node_child_process_1.execSync)(`
                            cd ${rootDir};
                            chmod +x docker/scripts/dev/ensure-ssh-keys-working.sh;
                            chmod +x docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;
                            chmod +x docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;
                            chmod +x docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;
                            chmod +x docker/scripts/dev/sync-to-local-04-db-restore-data.sh;
                            chmod +x docker/scripts/dev/sync-to-local-05-rsync.sh;
                        `, { stdio: 'inherit' });
                }
            },
            {
                title: 'Ensure environment is down',
                task: async () => {
                    const DownC = new down_1.default([], this.config);
                    await DownC.run();
                }
            },
            {
                title: 'Remove the database volume',
                task: async () => {
                    (0, node_child_process_1.execSync)('docker volume rm buzzingpixel_db-volume;', { stdio: 'inherit' });
                }
            },
            {
                title: 'Bring the environment online',
                task: async () => {
                    const UpC = new up_1.default([], this.config);
                    await UpC.run();
                }
            },
            {
                title: 'Set up the database',
                task: async () => {
                    const AppC = new app_1.default([
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
                    (0, node_child_process_1.execSync)(`
                            cd ${rootDir};
                            docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh up -d;
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-01-ssh-db-schema.sh;";
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-02-ssh-db-data.sh;";
                            docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-03-db-restore-schema.sh;";
                            docker exec buzzingpixel-db bash -c "/opt/project/docker/scripts/dev/sync-to-local-04-db-restore-data.sh;";
                            docker exec buzzingpixel-ssh bash -c "/opt/project/docker/scripts/dev/sync-to-local-05-rsync.sh;";
                            docker compose -f docker-compose.sync.to.local.yml -p buzzingpixel-ssh down;
                        `, { stdio: 'inherit' });
                }
            },
        ]);
        tasks.run();
    }
}
exports.default = Sync;
Sync.summary = `Syncs production database and content to local environment`;
