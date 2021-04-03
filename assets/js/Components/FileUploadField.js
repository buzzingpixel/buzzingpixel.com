import SupportsAjaxUploadWithProgress from './SupportsAjaxUploadWithProgress.js';

const modeIncompatible = 'incompatible';
const modeReadyForUpload = 'readyForUpload';
const modeDragInProgress = 'dragInProgress';

const preventDefault = (e) => {
    e.preventDefault();
    e.stopPropagation();
};

class FileUploadField {
    constructor (model) {
        if (!SupportsAjaxUploadWithProgress()) {
            model.data.mode = modeIncompatible;

            return;
        }

        this.model = model;

        this.progressBar = model.el.querySelector(
            '[ref="progressBar"]',
        );

        this.manualFileSelectInput = model.el.querySelector(
            '[name="manual_file_select_input"]',
        );

        model.data.mode = modeReadyForUpload;

        // Prevent default on drag actions
        [
            'drag',
            'dragstart',
            'dragend',
            'dragover',
            'dragenter',
            'dragleave',
            'drop',
        ].forEach((eventName) => {
            model.el.addEventListener(eventName, preventDefault);
        });

        // Trigger state changes

        // Drag file
        [
            'dragover',
            'dragenter',
        ].forEach((eventName) => {
            model.el.addEventListener(
                eventName,
                () => { this.setDragInProgress(); },
            );
        });

        // End file drag
        [
            'dragleave',
            'dragend',
            'drop',
        ].forEach((eventName) => {
            model.el.addEventListener(
                eventName,
                () => { this.setDragNotInProgress(); },
            );
        });

        // Drop the file listener
        model.el.addEventListener('drop', (e) => { this.handleDrop(e); });

        // Manual file upload listener
        this.manualFileSelectInput.onchange = (e) => {
            this.handleManualFile(e);
        };
    }

    setDragInProgress () {
        this.model.data.mode = modeDragInProgress;
    }

    setDragNotInProgress () {
        this.model.data.mode = modeReadyForUpload;
    }

    handleDrop (e) {
        this.model.data.message = '';

        this.progressBar.style.width = '0%';

        this.model.data.uploadInProgress = true;

        [...e.dataTransfer.files].forEach((file) => {
            this.uploadFile(file);
        });
    }

    handleManualFile (e) {
        this.model.data.message = '';

        this.progressBar.style.width = '0%';

        this.model.data.uploadInProgress = true;

        this.uploadFile(e.target.files[0]);
    }

    uploadFile (file) {
        const self = this;

        const { csrf } = window.appInfo;

        const formData = new FormData();

        formData.set(csrf.tokenNameKey, csrf.tokenName);

        formData.set(csrf.tokenValueKey, csrf.tokenValue);

        formData.append('file', file);

        // TODO: Set an authentication key for unauthenticated users if we ever
        //  need unauthenticated file uploads

        window.axios.post(
            '/ajax/file-upload',
            formData,
            {
                onUploadProgress (e) {
                    const percent = Math.round((e.loaded * 100) / e.total);

                    self.progressBar.style.width = `${String(percent)}%`;
                },
            },
        )
            .then((resp) => {
                self.model.data.messageType = 'success';
                self.model.data.message = resp.data.fileName;
                self.model.data.filePath = resp.data.filePath;
                self.model.data.fileName = resp.data.fileName;
            })
            .catch(() => {
                self.model.data.messageType = 'error';
                self.model.data.message = 'File upload failed';
            })
            .finally(() => {
                this.model.data.uploadInProgress = false;
            });
    }
}

export default (model) => {
    // eslint-disable-next-line no-new
    new FileUploadField(model);
};
