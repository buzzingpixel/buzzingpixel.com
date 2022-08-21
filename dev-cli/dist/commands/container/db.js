"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
class Db extends core_1.Command {
    async run() {
        this.log(chalk_1.default.yellow("You're working inside the 'db' application container of this project."));
        if (this.argv.length) {
            (0, node_child_process_1.execSync)(`docker exec -it buzzingpixel-db bash -c "${this.argv.join(' ')}";`, { stdio: 'inherit' });
            return;
        }
        this.log(chalk_1.default.yellow("Remember to exit when you're done"));
        (0, node_child_process_1.execSync)('docker exec -it buzzingpixel-db bash;', { stdio: 'inherit' });
    }
}
exports.default = Db;
// Allow variable arguments
Db.strict = false;
Db.summary = `Execute command in the ${chalk_1.default.cyan('db')} container. Empty argument starts a bash session`;
Db.description = 'If this command is run without arguments, a bash session will be started in the web container and you will be placed in that bash session. However, any arguments provided will, instead, be passed into and run in the bash session and then the session will exit. The latter is most often how you will use this command.';
Db.args = [
    {
        name: 'cmd',
        description: 'command',
        default: null,
    },
];
