// let love = document.querySelectorAll(".love");
// let heart = document.querySelectorAll(".fa-heart");

// love.forEach((love, i) => {
//   love.addEventListener("click", () => {
//     heart[i].classList.toggle("fa-regular");
//     heart[i].classList.toggle("fa-solid");
//   });
// });



const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}


