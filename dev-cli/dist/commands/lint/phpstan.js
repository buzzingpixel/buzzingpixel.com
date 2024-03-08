"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const Constants_1 = require("../../Constants");
class Phpstan extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        try {
            (0, node_child_process_1.execSync)(`
                    cd ${rootDir};
                    php -d memory_limit=4G vendor/phpstan/phpstan/phpstan analyse ${Constants_1.PhpLintingPaths};
                `, { stdio: 'inherit' });
        }
        catch (error) {
        }
    }
}
exports.default = Phpstan;
Phpstan.summary = 'Run PHPStan on all project files';
