const fileInput = document.getElementById('document-file');
const dropzone = document.getElementById('file-dropzone');
const selectedFile = document.getElementById('selected-file');
const selectedFileName = document.getElementById('selected-file-name');
const selectedFileSize = document.getElementById('selected-file-size');
const removeFileButton = document.getElementById('remove-selected-file');

if (fileInput && dropzone && selectedFile && selectedFileName && selectedFileSize && removeFileButton) {
    const showSelectedFile = (file) => {
        if (!file) {
            selectedFile.hidden = true;
            selectedFileName.textContent = '';
            selectedFileSize.textContent = '';

            return;
        }

        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = `${(file.size / (1024 * 1024)).toFixed(2)} MB`;
        selectedFile.hidden = false;
    };

    fileInput.addEventListener('change', () => showSelectedFile(fileInput.files[0]));

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('is-dragging');
    });

    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('is-dragging'));
    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragging');

        if (event.dataTransfer.files.length > 0) {
            fileInput.files = event.dataTransfer.files;
            showSelectedFile(fileInput.files[0]);
        }
    });

    removeFileButton.addEventListener('click', () => {
        fileInput.value = '';
        showSelectedFile(null);
    });
}
