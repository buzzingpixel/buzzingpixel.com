import { Command } from '@oclif/core';
export default class Up extends Command {
    static summary: string;
    run(): Promise<void>;
}
