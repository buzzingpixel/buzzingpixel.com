import { Command } from '@oclif/core';
export default class DbBackup extends Command {
    static summary: string;
    run(): Promise<void>;
}
