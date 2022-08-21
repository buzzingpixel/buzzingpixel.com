import { Command } from '@oclif/core';
export default class Node extends Command {
    static strict: boolean;
    static summary: string;
    static description: string;
    static args: {
        name: string;
        description: string;
        default: null;
    }[];
    run(): Promise<void>;
}
