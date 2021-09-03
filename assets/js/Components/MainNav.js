const run = (data) => {
    window.axios.get('/ajax/user/payload')
        .then((obj) => {
            data.userIsLoggedIn = obj.data.userIsLoggedIn;
            data.userEmailAddress = obj.data.userEmailAddress;
            data.userIsAdmin = obj.data.userIsAdmin;
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
