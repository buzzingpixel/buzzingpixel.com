"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const listr_1 = (0, tslib_1.__importDefault)(require("listr"));
class DbRestore extends core_1.Command {
    async run() {
        const { args } = await this.parse(DbRestore);
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        const backupDir = `${rootDir}/docker/localStorage/dbBackups`;
        if (!args.file) {
            fs.mkdirpSync(backupDir);
            const files = fs.readdirSync(backupDir);
            this.log(chalk_1.default.cyan('Provide a filename argument. Available files:'));
            files.forEach((file) => {
                this.log(`  ${chalk_1.default.cyan(file)}`);
            });
            return;
        }
        const filePath = `${backupDir}/${args.file}`;
        const fileExists = fs.existsSync(filePath);
        if (!fileExists) {
            this.log(chalk_1.default.red('The specified file does not exist'));
            return;
        }
        const tasks = new listr_1.default([{
                title: `Restoring database from ${filePath}`,
                task: () => {
                    (0, node_child_process_1.execSync)(`
                        docker cp ${filePath} buzzingpixel-db:/dump.psql;
                        docker exec -it buzzingpixel-db bash -c 'PGPASSWORD="\${DB_PASSWORD}"; pg_restore --clean -U "\${DB_USER}" -d "\${DB_DATABASE}" -v < "/dump.psql"';
                        docker exec -it buzzingpixel-db bash -c "rm /dump.psql";
                    `, { stdio: 'inherit' });
                },
            }]);
        await tasks.run();
    }
}
exports.default = DbRestore;
// Allow variable arguments
DbRestore.strict = false;
DbRestore.summary = 'Restore database from file in backup directory';
DbRestore.args = [
    {
        name: 'file',
        description: 'File name to restore',
        default: null,
    },
];
