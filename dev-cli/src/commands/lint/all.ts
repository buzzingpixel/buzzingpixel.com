import { Command } from '@oclif/core';
import Phpcs from './phpcs';
import Phpstan from './phpstan';

export default class All extends Command {
    public static summary = 'Run all PHP linters';

    public async run (): Promise<void> {
        const PhpcsC = new Phpcs([], this.config);
        await PhpcsC.run();

        const PhpstanC = new Phpstan([], this.config);
        await PhpstanC.run();
    }
}
