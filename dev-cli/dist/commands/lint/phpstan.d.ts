import { Command } from '@oclif/core';
export default class Phpstan extends Command {
    static summary: string;
    run(): Promise<void>;
}
