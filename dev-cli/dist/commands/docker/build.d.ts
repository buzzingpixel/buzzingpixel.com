import { Command } from '@oclif/core';
export default class Build extends Command {
    static summary: string;
    run(): Promise<void>;
}
