const input = document.querySelector('input[name="real_name"]');
const errorMessage = document.createElement("div");
errorMessage.style.color = "red";

input.addEventListener("change", function() {
  if (!validateName(this.value)) {
    errorMessage.textContent = "Por favor, insira seu nome completo";
    this.after(errorMessage);
  } else {
    errorMessage.textContent = "";
  }
});