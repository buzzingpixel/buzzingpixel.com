const supportFileAPI = () => {
    const fi = document.createElement('INPUT');
    fi.type = 'file';
    return 'files' in fi;
};

const supportAjaxUploadProgressEvents = () => {
    const xhr = new XMLHttpRequest();
    return !!(xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
};

const supportFormData = () => !!window.FormData;

export default () => supportFileAPI()
    && supportAjaxUploadProgressEvents()
    && supportFormData();
