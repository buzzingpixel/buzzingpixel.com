import { Command } from '@oclif/core';
export default class Restart extends Command {
    static summary: string;
    static description: string;
    run(): Promise<void>;
}
