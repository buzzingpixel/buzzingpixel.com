"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const tslib_1 = require("tslib");
const core_1 = require("@oclif/core");
const phpcs_1 = (0, tslib_1.__importDefault)(require("./phpcs"));
const phpstan_1 = (0, tslib_1.__importDefault)(require("./phpstan"));
class All extends core_1.Command {
    async run() {
        const PhpcsC = new phpcs_1.default([], this.config);
        await PhpcsC.run();
        const PhpstanC = new phpstan_1.default([], this.config);
        await PhpstanC.run();
    }
}
exports.default = All;
All.summary = 'Run all PHP linters';
