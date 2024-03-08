"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
const node_child_process_1 = require("node:child_process");
class DumperToFile extends core_1.Command {
    async run() {
        (0, node_child_process_1.execSync)(`
                docker exec -it -w /var/www buzzingpixel-app bash -c "XDEBUG_MODE=off php cli server:dump --format=html > /opt/project/storage/dump.html" || true;
            `, { stdio: 'inherit' });
    }
}
exports.default = DumperToFile;
DumperToFile.summary = 'Start server:dump to HTML file';
