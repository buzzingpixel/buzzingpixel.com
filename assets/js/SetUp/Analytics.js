const run = () => {
    const { csrf } = window.appInfo;

    const requestObject = {
        uri: window.location.pathname,
    };

    requestObject[csrf.tokenNameKey] = csrf.tokenName;

    requestObject[csrf.tokenValueKey] = csrf.tokenValue;

    window.axios.post('/ajax/analytics/page-view', requestObject);
};

const preRun = () => {
    if (!window.axios) {
        setTimeout(() => {
            preRun();
        }, 50);

        return;
    }

    run();
};

export default () => {
    preRun();
};
