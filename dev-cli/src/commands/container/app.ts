import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';
import chalk from 'chalk';

export default class App extends Command {
    // Allow variable arguments
    public static strict = false;

    public static summary = `Execute command in the ${chalk.cyan('app')} container. Empty argument starts a bash session`;

    public static description = 'If this command is run without arguments, a bash session will be started in the web container and you will be placed in that bash session. However, any arguments provided will, instead, be passed into and run in the bash session and then the session will exit. The latter is most often how you will use this command.';

    public static args = [
        {
            name: 'cmd',
            description: 'command',
            default: null,
        },
    ];

    public async run (): Promise<void> {
        this.log(chalk.yellow(
            "You're working inside the 'app' application container of this project.",
        ));

        if (this.argv.length) {
            execSync(
                `docker exec -it -e XDEBUG_MODE=off -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off ${this.argv.join(' ')}";`,
                { stdio: 'inherit' },
            );

            return;
        }

        this.log(chalk.yellow("Remember to exit when you're done"));

        execSync(
            'docker exec -it -e XDEBUG_MODE=off -w /opt/project buzzingpixel-app bash;',
            { stdio: 'inherit' },
        );
    }
}
