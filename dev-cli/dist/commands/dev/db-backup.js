"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const listr_1 = (0, tslib_1.__importDefault)(require("listr"));
class DbBackup extends core_1.Command {
    async run() {
        const date = new Date();
        const y = date.getFullYear();
        const m = (date.getMonth() + 1).toString().padStart(2, '0');
        const d = date.getDate();
        const h = date.getHours();
        const i = date.getMinutes();
        const s = date.getSeconds();
        let dateStr = `${y}-${m}-${d}__${h}-${i}-${s}`;
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        const tasks = new listr_1.default([{
                title: `Backing up database to docker/localStorage/dbBackups/${dateStr}.psql`,
                task: async () => {
                    await new Promise(r => setTimeout(r, 500));
                    fs.mkdirpSync(`${rootDir}/docker/localStorage/dbBackups`);
                    (0, node_child_process_1.execSync)("docker exec buzzingpixel-db bash -c 'pg_dump --dbname=postgresql://${DB_USER}:${DB_PASSWORD}@127.0.0.1:5432/${DB_DATABASE} -w -Fc > /dump.psql';", { stdio: 'inherit' });
                    (0, node_child_process_1.execSync)(`
                docker cp buzzingpixel-db:/dump.psql ${rootDir}/docker/localStorage/dbBackups/${dateStr}.psql;
            `, { stdio: 'inherit' });
                    (0, node_child_process_1.execSync)('docker exec ${interactiveArgs} buzzingpixel-db bash -c "rm /dump.psql";', { stdio: 'inherit' });
                }
            }]);
        await tasks.run();
    }
}
exports.default = DbBackup;
DbBackup.summary = 'Backup the local database';
