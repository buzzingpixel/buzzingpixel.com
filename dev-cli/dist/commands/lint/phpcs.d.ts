import { Command } from '@oclif/core';
export default class Phpcs extends Command {
    static summary: string;
    run(): Promise<void>;
}
