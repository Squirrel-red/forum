const buttons = document.querySelectorAll(".toggle-form");
const forms = document.querySelectorAll(".response-form");

buttons.forEach((button, index) => {
  button.addEventListener("click", function () {
    forms[index].classList.toggle("response-form");
  });
});