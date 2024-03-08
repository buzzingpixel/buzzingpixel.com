import { Command } from '@oclif/core';
import chalk from 'chalk';
import Up from './up';
import Down from './down';

export default class Restart extends Command {
    public static summary = `Runs ${chalk.cyan('docker down')} then ${chalk.cyan('docker up')}.`;

    public static description = 'This is useful as a single command instead of having to run two commands if you\'re having some trouble you suspect is related to the containers being in a strange state of some kind. This basically ensures your containers are running from the clean images.';

    public async run (): Promise<void> {
        const DownC = new Down(this.argv, this.config);
        await DownC.run();

        const UpC = new Up(this.argv, this.config);
        await UpC.run();
    }
}
