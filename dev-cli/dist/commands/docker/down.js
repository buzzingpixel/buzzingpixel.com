"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
class Down extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        this.log(chalk_1.default.cyan('Stopping the Docker environment…'));
        (0, node_child_process_1.execSync)(`
                cd ${rootDir};
                docker compose -f docker-compose.yml -f docker-compose.dev.yml -p buzzingpixel down;
            `, { stdio: 'inherit' });
        this.log(chalk_1.default.green('Docker environment is offline.'));
    }
}
exports.default = Down;
Down.summary = 'Stop Docker environment';
