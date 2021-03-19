const run = (data) => {
    document.body.addEventListener('click', (e) => {
        if (e.target.closest('[data-name="AccountMenu"]') !== null) {
            return;
        }

        data.accountMenuIsActive = false;
    });

    window.axios.get('/ajax/user/payload')
        .then((obj) => {
            data.userIsLoggedIn = obj.data.userIsLoggedIn;
            data.userEmailAddress = obj.data.userEmailAddress;
        });
};

const firstRun = (data) => {
    run(data);
};

const preRun = (data) => {
    if (!window.axios) {
        setTimeout(() => {
            preRun(data);
        }, 50);

        return;
    }

    firstRun(data);
};

export default (data) => {
    preRun(data);
};
