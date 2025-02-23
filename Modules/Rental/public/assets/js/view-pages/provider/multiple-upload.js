"use strict";
$(document).ready(function () {
    const MAX_FILE_SIZE_MB = 1;
    const MAX_FILES = 5;
    const ALLOWED_FILE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    const imageContainer = document.getElementById("image_container");
    const imageUploadWrapper = document.getElementById("image_upload_wrapper");
    const inputElement = document.querySelector('.multiple_image_input');
    const fileSet = new Set();

    inputElement.addEventListener('change', function (event) {

        const maximum = $(this).data('maximum');
        const content = $(this).data('content');
        const type = $(this).data('type');
        const size = $(this).data('size');
        const files = Array.from(event.target.files);
        const currentFiles = imageContainer.querySelectorAll(".image-single").length;

        if (currentFiles + files.length > MAX_FILES) {
            toastr.error(maximum + MAX_FILES + content, {
                CloseButton: true,
                ProgressBar: true
            });
            return;
        }
        files.forEach(file => {
            // Validate file type
            if (!ALLOWED_FILE_TYPES.includes(file.type)) {
                toastr.error(type, {
                    CloseButton: true,
                    ProgressBar: true
                });
                return;
            }

            // Validate file size
            if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                toastr.error(size, {
                    CloseButton: true,
                    ProgressBar: true
                });
                return;
            }

            // Add to the file set and create preview
            if (!fileSet.has(file.name)) {
                fileSet.add(file.name);

                const fileURL = URL.createObjectURL(file);
                const imageSingle = document.createElement("div");
                imageSingle.className = "image-single h-100 max-w-200px p-0";
                imageSingle.innerHTML = `
                            <a href="javascript:void(0);" class="remove-btn" onclick="removeImage(event, this, '${file.name}')">
                                <i class="tio-clear"></i>
                            </a>
                            <img class="img--vertical-2 rounded-10" width="200" height="100" loading="lazy" src="${fileURL}" alt="">
                        `;
                imageContainer.appendChild(imageSingle);
            }
        });

        toggleUploadWrapper();
    });

    window.removeImage = function (event, element, fileName) {
        event.stopPropagation();
        const imageSingle = element.closest(".image-single");
        imageSingle.remove();
        fileSet.delete(fileName); // Remove the file from the set
        toggleUploadWrapper();
    };

    function toggleUploadWrapper() {
        const currentFiles = imageContainer.querySelectorAll(".image-single").length;
        imageUploadWrapper.style.display = currentFiles >= 5 ? "none" : "block";
    }
    // Handle reset button click
    $('#reset_btn').click(function () {
        // Select and remove only the uploaded image elements
        const uploadedImages = imageContainer.querySelectorAll(".image-single");
        uploadedImages.forEach(image => image.remove());

        // Clear the file set
        fileSet.clear();

        // Ensure the upload wrapper is visible
        imageUploadWrapper.style.display = "block";
    });

});

// ----- mutiple document upload
$(document).ready(function () {
    const MAX_FILES = 5;
    const pdfContainer = document.getElementById("pdf-container");
    const documentUploadWrapper = document.getElementById("upload-wrapper");
    const uploadedFiles = new Map(); // Store files with unique names as keys

    // Handle file selection and upload
    document.querySelector('.multiple_document_input').addEventListener('change', function (event) {
        const files = Array.from(event.target.files);
        const currentFiles = pdfContainer.querySelectorAll(".pdf-single").length;

        if (currentFiles + files.length > MAX_FILES) {
            toastr.error(`You can upload a maximum of ${MAX_FILES} files.`, {
                CloseButton: true,
                ProgressBar: true,
            });
            return;
        }

        files.forEach((file) => {
            if (!uploadedFiles.has(file.name)) {
                uploadedFiles.set(file.name, file); // Store the file with its name as the key

                const fileURL = URL.createObjectURL(file);
                const fileName = file.name;
                const fileType = file.type;

                const pdfSingle = document.createElement("div");
                pdfSingle.className = "pdf-single";
                pdfSingle.setAttribute("data-file-name", fileName);
                pdfSingle.setAttribute("onclick", `window.open('${fileURL}', '_blank')`);

                const iconSrc = fileType.startsWith("image/") ?
                    "{{ asset('public/assets/admin/img/picture.svg') }}" :
                    "{{ asset('public/assets/admin/img/document.svg') }}";

                pdfSingle.innerHTML = `
                            <div class="pdf-frame">
                                <canvas class="pdf-preview" style="display: none;"></canvas>
                                <img class="pdf-thumbnail" src="{{ asset('public/assets/admin/img/blank2.png') }}" alt="File Thumbnail">
                            </div>
                            <div class="overlay">
                                <a href="javascript:void(0);" class="remove-btn" onclick="removeDocument(event, this)">
                                    <i class="tio-clear"></i>
                                </a>
                                <div class="pdf-info d-flex gap-10px align-items-center">
                                    <img src="${iconSrc}" width="34" alt="File Type Logo">
                                    <div class="fs-13 text--title d-flex flex-column">
                                        <span class="file-name">${fileName}</span>
                                        <span class="opacity-50">Click to view the file</span>
                                    </div>
                                </div>
                            </div>
                        `;

                pdfContainer.appendChild(pdfSingle);

                // Log file details in the console
                console.log(`File added: ${fileName}, URL: ${fileURL}`);

                // Render the thumbnail (if applicable)
                renderFileThumbnail(pdfSingle, fileType);

                // Show success notification
                toastr.success("File added successfully.", {
                    CloseButton: true,
                    ProgressBar: true,
                });
            }
        });

        toggleUploadWrapper();

        // Clear file input after upload
        // event.target.value = "";

    });

    // Remove document handler
    window.removeDocument = function (event, element) {
        event.stopPropagation();
        const pdfSingle = element.closest(".pdf-single");
        const fileName = pdfSingle.getAttribute("data-file-name");

        // Remove file from the Map
        uploadedFiles.delete(fileName);

        pdfSingle.remove();
        toggleUploadWrapper();
    };

    // Toggle visibility of upload wrapper
    function toggleUploadWrapper() {
        const currentFiles = pdfContainer.querySelectorAll(".pdf-single").length;
        documentUploadWrapper.style.display = currentFiles >= MAX_FILES ? "none" : "block";
    }

    // Render file thumbnail (image, PDF, or other file types)
    async function renderFileThumbnail(element, fileType) {
        const fileUrl = element.getAttribute("onclick").match(/'(.*?)'/)[1];
        const canvas = element.querySelector(".pdf-preview");
        const thumbnail = element.querySelector(".pdf-thumbnail");

        try {
            if (fileType.startsWith("image/")) {
                thumbnail.src = fileUrl; // Directly use the image URL
            } else if (fileType === "application/pdf") {
                const ctx = canvas.getContext("2d");
                const loadingTask = pdfjsLib.getDocument(fileUrl);
                const pdf = await loadingTask.promise;
                const page = await pdf.getPage(1);

                const viewport = page.getViewport({ scale: 0.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                await page.render({ canvasContext: ctx, viewport }).promise;
                thumbnail.src = canvas.toDataURL(); // Render PDF thumbnail
            } else {
                // Use a fallback thumbnail for unsupported file types
                thumbnail.src = "{{ asset('public/assets/admin/img/blank2.png') }}";
            }

            thumbnail.style.display = "block";
            canvas.style.display = "none";
        } catch (error) {
            console.error("Error rendering file thumbnail:", error);
        }
    }

    // Handle form submission
    $('form').on('submit', function (e) {
        // e.preventDefault();

        const formData = new FormData(this);

        // Append all files to FormData
        uploadedFiles.forEach((file, fileName) => {
            formData.append('documents[]', file, fileName);
        });

        // Log form data to the console
        console.log('Files submitted:');
        uploadedFiles.forEach((file, fileName) => {
            console.log(`${fileName}:`, file);
        });
    });

    // Reset button handler
    $('#reset_btn').click(function () {
        const uploadedDocuments = pdfContainer.querySelectorAll(".pdf-single");
        uploadedDocuments.forEach((doc) => doc.remove());
        uploadedFiles.clear();
        documentUploadWrapper.style.display = "block";
    });
});
