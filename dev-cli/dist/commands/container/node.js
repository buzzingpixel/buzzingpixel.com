"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
const fs = (0, tslib_1.__importStar)(require("fs-extra"));
class Node extends core_1.Command {
    async run() {
        const rootDir = fs.realpathSync(`${this.config.root}/../`);
        this.log(chalk_1.default.yellow("You're working inside the 'node' application container of this project."));
        if (this.argv.length) {
            (0, node_child_process_1.execSync)(`
                    docker run -it --rm \
                        --name buzzingpixel-node \
                        -v "${rootDir}:/app" \
                        -w /app \
                        node:15.8.0 bash -c "${this.argv.join(' ')}";
                `, { stdio: 'inherit' });
            return;
        }
        this.log(chalk_1.default.yellow("Remember to exit when you're done"));
        (0, node_child_process_1.execSync)(`
                docker run -it --rm \
                    --name buzzingpixel-node \
                    -v "${rootDir}:/app" \
                    -w /app \
                    node:15.8.0 bash;
            `, { stdio: 'inherit' });
    }
}
exports.default = Node;
// Allow variable arguments
Node.strict = false;
Node.summary = `Execute command in the ${chalk_1.default.cyan('node')} container. Empty argument starts a bash session`;
Node.description = 'If this command is run without arguments, a bash session will be started in the web container and you will be placed in that bash session. However, any arguments provided will, instead, be passed into and run in the bash session and then the session will exit. The latter is most often how you will use this command.';
Node.args = [
    {
        name: 'cmd',
        description: 'command',
        default: null,
    },
];
