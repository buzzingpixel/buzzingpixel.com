/* eslint-disable no-unused-vars */

window.Methods = {};

function RunWhenReady (run, data) {
    let tries = 0;

    function tryRun () {
        if (tries > 200) {
            throw new Error(`Method ${run} never became available`);
        }

        if (window.Methods[run] === undefined) {
            tries += 1;

            setTimeout(() => {
                tryRun();
            }, 100);

            return;
        }

        window.Methods[run](data);
    }

    tryRun();
}
