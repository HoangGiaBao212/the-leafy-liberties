class Toast {
  constructor(params) {
    this.params = params;
    this.toast = document.createElement("div");
    this.toast.classList.add(
      "fixed",
      "top-0",
      "right-0",
      "z-[200]",
      "w-1/2",
      "max-w-sm",
      "p-4",
      "m-4",
      "bg-white",
      "rounded-lg",
      "shadow-lg",
      "pointer-events-none",
      "transition-all",
      "duration-300",
      "ease-in-out",
      "transform",
      "translate-x-1/2",
      "translate-y-1/2",
      "opacity-0",
      "scale-95"
    );
    this.toast.innerHTML = `<div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
          ${
            params.type === "success"
              ? `<svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>`
              : params.type === "error"
              ? `<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>`
              : params.type === "info"
              ? `<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
              </svg>`
              : ""
          }
        </div>
        <div>
          <p class="text-sm font-medium text-gray-900">
            ${params.message}
          </p>
        </div>
      </div>`;
    document.body.appendChild(this.toast);
    const removeToast = () => {
      this.toast.classList.remove(
        "opacity-0",
        "scale-95",
        "translate-x-1/2",
        "translate-y-1/2"
      );
      this.toast.classList.add(
        "opacity-100",
        "scale-100",
        "translate-x-0",
        "translate-y-0"
      );
      setTimeout(() => {
        this.toast.classList.remove(
          "opacity-100",
          "scale-100",
          "translate-x-0",
          "translate-y-0"
        );
        this.toast.classList.add(
          "opacity-0",
          "scale-95",
          "translate-x-1/2",
          "translate-y-1/2"
        );
        setTimeout(() => {
          this.toast.remove();
        }, 300);
      }, 2000);
    }
    setTimeout(removeToast, 100);
  }
}

export default Toast;
