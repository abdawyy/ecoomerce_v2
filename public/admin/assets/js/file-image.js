let queuedImageArray = [];
let inputDiv = document.querySelector(".uploadDiv");
let input = document.getElementById("file");
let queuedForm = document.getElementById("queued-form");
let queuedDev = document.querySelector(".queued-dev");

input.addEventListener("change", () => {
  const files = input.files;
  for (let i = 0; i < files.length; i++) {
    queuedImageArray.push(files[i]);
  }
  //   queuedForm.reset();
  displayQueuedImage();
});

inputDiv.addEventListener("drop", (e) => {
   e.preventDefault();
  const files = e.dataTransfer.files;
  for (let i = 0; i < files.length; i++) {
    if (!files[i].type.match("image")) continue;

    if (queuedImageArray.every((image) => image.name !== files[i].name))
      queuedImageArray.push(files[i]);
  }
  displayQueuedImage();
  console.log(files)
});

function displayQueuedImage() {
  let images = "";
  queuedImageArray.forEach((image, index) => {
    images +=
    `<img src="${URL.createObjectURL(image)}" class="img-fluid" alt="...">`;
  });
  queuedDev.innerHTML = images;
}
