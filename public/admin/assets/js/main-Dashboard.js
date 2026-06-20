const sidebarBtn = document.querySelector(".toggle-sidebar-btn");
const mainContent = document.getElementById("main");

function closeMobileSidebar() {
    if (window.innerWidth <= 1199) {
        document.body.classList.remove("toggle-sidebar");
    }
}

if (sidebarBtn) {
    sidebarBtn.addEventListener("click", (event) => {
        event.stopPropagation();
        document.body.classList.toggle("toggle-sidebar");
    });
}

if (mainContent) {
    mainContent.addEventListener("click", closeMobileSidebar);
}

document.querySelector(".sidebar")?.addEventListener("click", (event) => {
    if (event.target.closest("a")) {
        closeMobileSidebar();
    }
});

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        closeMobileSidebar();
    }
});

// image view -------------------------------------------------//
const input1 = document.getElementById("file1");
if (input1) {
    let toastElement = document.getElementById("error-toast");
    let toast = toastElement ? new bootstrap.Toast(toastElement) : null;

    let imgDisplay = document.querySelectorAll(".imgDisplay");
    let ImagePreview0 = document.querySelectorAll(".ImagePreview0");
    let ImagePreview1 = document.querySelectorAll(".ImagePreview1");
    let ImagePreview2 = document.querySelectorAll(".ImagePreview2");
    let ImagePreview3 = document.querySelectorAll(".ImagePreview3");
    let inputDiv = document.querySelector(".uploadDiv");
    let addSubImageBtn = document.getElementById("addSubImageBtn");
    let ImageArray = [];

    input1.addEventListener("change", () => {
        if (ImageArray.length < 4) {
            const files = input1.files;
            let isValid = true;
            for (let i = 0; i < files.length; i++) {
                const fileType = files[i].type;
                if (!fileType.match("image.*")) {
                    isValid = false;
                    displayToastErrorMessage("Only image files are allowed.");
                    break;
                }
            }
            if (isValid) {
                clearToastErrorMessage();
                for (let i = 0; i < files.length; i++) {
                    ImageArray.push(files[i]);
                }
                input1.disabled = true;
                input1.style.cursor = "not-allowed";
                if (inputDiv) inputDiv.style.backgroundColor = "lightgray";
            }
            displayQueuedImage();
            uploadImages();
        } else {
            displayToastErrorMessage("You can't upload more than 4 images.");
        }
    });

    if (inputDiv) {
        inputDiv.addEventListener("drop", (e) => {
            if (ImageArray.length < 4) {
                e.preventDefault();
                const files = e.dataTransfer.files;
                let isValid = true;
                for (let i = 0; i < files.length; i++) {
                    const fileType = files[i].type;
                    if (!fileType.match("image.*")) {
                        isValid = false;
                        displayToastErrorMessage("Only image files are allowed.");
                        break;
                    }
                }
                if (isValid) {
                    clearToastErrorMessage();
                    for (let i = 0; i < files.length; i++) {
                        if (ImageArray.every((image) => image.name !== files[i].name)) {
                            ImageArray.push(files[i]);
                        }
                    }
                    input1.disabled = true;
                    input1.style.cursor = "not-allowed";
                    inputDiv.style.backgroundColor = "lightgray";
                }
                displayQueuedImage();
                uploadImages();
            }
        });
    }

    if (addSubImageBtn) {
        addSubImageBtn.addEventListener("click", () => {
            if (ImageArray.length < 4) {
                input1.disabled = false;
                input1.style.cursor = "pointer";
                if (inputDiv) inputDiv.style.backgroundColor = "transparent";
                displayQueuedImage();
            } else {
                displayToastErrorMessage("You can't upload more than 4 images.");
            }
        });
    }

    function displayQueuedImage() {
        for (let i = 0; i < ImageArray.length; i++) {
            if (imgDisplay[i]) imgDisplay[i].src = URL.createObjectURL(ImageArray[i]);
        }
        if (ImageArray.length === 1) {
            for (let i = 0; i < ImagePreview0.length; i++) {
                ImagePreview0[i].src = URL.createObjectURL(ImageArray[0]);
            }
        }
        if (ImageArray.length === 2) {
            for (let i = 0; i < ImagePreview0.length; i++) {
                ImagePreview0[i].src = URL.createObjectURL(ImageArray[0]);
            }
            if (ImagePreview1[0]) ImagePreview1[0].src = URL.createObjectURL(ImageArray[1]);
        }
        if (ImageArray.length === 3) {
            for (let i = 0; i < ImagePreview0.length; i++) {
                ImagePreview0[i].src = URL.createObjectURL(ImageArray[0]);
            }
            if (ImagePreview1[0]) ImagePreview1[0].src = URL.createObjectURL(ImageArray[1]);
            if (ImagePreview2[0]) ImagePreview2[0].src = URL.createObjectURL(ImageArray[2]);
        }
        if (ImageArray.length === 4) {
            for (let i = 0; i < ImagePreview0.length; i++) {
                ImagePreview0[i].src = URL.createObjectURL(ImageArray[0]);
            }
            if (ImagePreview1[0]) ImagePreview1[0].src = URL.createObjectURL(ImageArray[1]);
            if (ImagePreview2[0]) ImagePreview2[0].src = URL.createObjectURL(ImageArray[2]);
            if (ImagePreview3[0]) ImagePreview3[0].src = URL.createObjectURL(ImageArray[3]);
        }
    }

    function uploadImages() {
        const formData = new FormData();
        ImageArray.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });
    }

    function displayToastErrorMessage(message) {
        if (!toast) return;
        let toastBody = document.querySelector("#error-toast .toast-body");
        if (toastBody) toastBody.innerText = message;
        toast.show();
    }

    function clearToastErrorMessage() {
        let toastBody = document.querySelector("#error-toast .toast-body");
        if (toastBody) toastBody.innerText = "";
    }
}

// card view preview -------------------------------------------------//
const productNameInput = document.getElementById("productName");
const productNameCard = document.getElementById("productNameCard");
const productNameCard2 = document.getElementById("productNameCard2");
const DescriptionInput = document.getElementById("Description");
const DescriptionCard = document.getElementById("DescriptionCard");
const DescriptionCard2 = document.getElementById("DescriptionCard2");
const SalePriceInput = document.getElementById("SalePrice");
const SalePriceCard = document.getElementById("SalePriceCard");
const SalePriceCard2 = document.getElementById("SalePriceCard2");
const RegularPriceInput = document.getElementById("RegularPrice");
const RegularPriceCard = document.getElementById("RegularPriceCard");

if (productNameInput && productNameCard) {
    function replaceProductName() {
        if (productNameInput.value != "") {
            productNameCard.innerText = productNameInput.value;
            if (productNameCard2) productNameCard2.innerText = productNameInput.value;
        } else {
            productNameCard.innerText = "Product Name";
        }
    }
    productNameInput.addEventListener("keyup", replaceProductName);
}

if (DescriptionInput && DescriptionCard) {
    function replaceDescription() {
        if (DescriptionInput.value != "") {
            DescriptionCard.innerText = DescriptionInput.value;
            if (DescriptionCard2) DescriptionCard2.innerText = DescriptionInput.value;
        } else {
            DescriptionCard.innerText = "Description";
        }
    }
    DescriptionInput.addEventListener("keyup", replaceDescription);
}

if (SalePriceInput && SalePriceCard) {
    function replaceSalePrice() {
        if (SalePriceInput.value != "") {
            SalePriceCard.innerText = SalePriceInput.value;
            if (SalePriceCard2) SalePriceCard2.innerText = SalePriceInput.value;
        }
    }
    SalePriceInput.addEventListener("keyup", replaceSalePrice);
    SalePriceInput.addEventListener("change", replaceSalePrice);
}

if (RegularPriceInput && RegularPriceCard) {
    function replaceRegularPrice() {
        if (RegularPriceInput.value != "") {
            RegularPriceCard.innerText = RegularPriceInput.value;
        }
    }
    RegularPriceInput.addEventListener("keyup", replaceRegularPrice);
    RegularPriceInput.addEventListener("change", replaceRegularPrice);
}
