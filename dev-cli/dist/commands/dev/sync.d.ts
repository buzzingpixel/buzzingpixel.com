import { Command } from '@oclif/core';
export default class Sync extends Command {
    static summary: string;
    run(): Promise<void>;
}
