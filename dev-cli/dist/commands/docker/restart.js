"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const chalk_1 = (0, tslib_1.__importDefault)(require("chalk"));
const up_1 = (0, tslib_1.__importDefault)(require("./up"));
const down_1 = (0, tslib_1.__importDefault)(require("./down"));
class Restart extends core_1.Command {
    async run() {
        const DownC = new down_1.default(this.argv, this.config);
        await DownC.run();
        const UpC = new up_1.default(this.argv, this.config);
        await UpC.run();
    }
}
exports.default = Restart;
Restart.summary = `Runs ${chalk_1.default.cyan('docker down')} then ${chalk_1.default.cyan('docker up')}.`;
Restart.description = 'This is useful as a single command instead of having to run two commands if you\'re having some trouble you suspect is related to the containers being in a strange state of some kind. This basically ensures your containers are running from the clean images.';
