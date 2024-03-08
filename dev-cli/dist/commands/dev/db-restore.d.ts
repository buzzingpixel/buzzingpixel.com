import { Command } from '@oclif/core';
export default class DbRestore extends Command {
    static strict: boolean;
    static summary: string;
    static args: {
        name: string;
        description: string;
        default: null;
    }[];
    run(): Promise<void>;
}
