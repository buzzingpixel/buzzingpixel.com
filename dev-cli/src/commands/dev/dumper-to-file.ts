import { Command } from '@oclif/core';
import { execSync } from 'node:child_process';

export default class DumperToFile extends Command {
    public static summary = 'Start server:dump to HTML file';

    public async run (): Promise<void> {
        execSync(
            `
                docker exec -it -w /opt/project buzzingpixel-app bash -c "XDEBUG_MODE=off php cli server:dump --format=html > /opt/project/storage/dump.html" || true;
            `,
            { stdio: 'inherit' },
        );
    }
}
