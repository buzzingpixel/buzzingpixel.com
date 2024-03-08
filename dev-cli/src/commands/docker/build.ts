import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class Build extends Command {
    public static summary = 'Build Docker images';

    public async run (): Promise<void> {
        execSync(
            `
                cd ${this.config.root}/../;
                chmod +x docker/bin/*;
                docker/bin/build.sh;
            `,
            { stdio: 'inherit' },
        );
    }
}
