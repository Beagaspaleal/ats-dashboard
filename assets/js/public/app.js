export function ksToast(root = document) {
  const el = document.getElementById("ksToast");
  if(!el) return;
  el.classList.remove("text-bg-success","text-bg-danger","text-bg-warning","text-bg-info");
  el.classList.add(type === "success" ? "text-bg-success" : "text-bg-danger");
  el.querySelector(".toast-body").textContent = message;
  el.style.display = "block";

  const toast = new bootstrap.Toast(el, { delay: 2500 });
  toast.show();
}
