"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
const Constants_1 = require("../../Constants");
class Phpcs extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        try {
            (0, node_child_process_1.execSync)(`
                    cd ${rootDir};
                    vendor/bin/phpcs --config-set installed_paths ../../doctrine/coding-standard/lib,../../slevomat/coding-standard;
                    vendor/bin/phpcs ${Constants_1.PhpLintingPaths};
                `, { stdio: 'inherit' });
        }
        catch (error) {
        }
    }
}
exports.default = Phpcs;
Phpcs.summary = 'Run PHPCS validation on all project files';
