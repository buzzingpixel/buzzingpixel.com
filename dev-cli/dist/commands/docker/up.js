"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
class Up extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        this.log(chalk_1.default.cyan('Starting the Docker environment…'));
        (0, node_child_process_1.execSync)(`
                cd ${rootDir};
                chmod -R 0777 storage;
                docker compose -f docker-compose.dev.yml -p buzzingpixel up -d;
                docker exec -it buzzingpixel-app bash -c "chmod -R 0777 /var/www/storage";
            `, { stdio: 'inherit' });
        this.log(chalk_1.default.green('Docker environment is online.'));
    }
}
exports.default = Up;
Up.summary = 'Start Docker environment';
