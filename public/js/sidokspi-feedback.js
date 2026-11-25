(() => {
    const available = () => typeof window.Swal !== "undefined";
    window.SIDFeedback = {
        show(type, message) {
            if (!available()) {
                window.alert(message);
                return;
            }
            return Swal.fire({
                icon: type,
                title: type === "success" ? "Berhasil!" : "Gagal!",
                text: message,
                confirmButtonText: "OK",
                confirmButtonColor: "#2168d6",
            });
        },
    };
    document.addEventListener("submit", async (event) => {
        const form = event.target;
        if (!form.matches("form[data-confirm-delete]")) return;
        event.preventDefault();
        if (form.dataset.confirming) return;
        form.dataset.confirming = "1";
        try {
            const confirmed = available()
                ? (
                      await Swal.fire({
                          icon: "warning",
                          title: "Hapus data?",
                          text: "Data yang dihapus tidak dapat dikembalikan.",
                          showCancelButton: true,
                          confirmButtonText: "Ya, Hapus",
                          cancelButtonText: "Batal",
                          confirmButtonColor: "#d43d4b",
                          cancelButtonColor: "#64748b",
                          reverseButtons: true,
                          focusCancel: true,
                      })
                  ).isConfirmed
                : window.confirm("Yakin ingin menghapus data ini?");
            if (confirmed) {
                form.closest(
                    "#kategoriResults, #pengguna-results",
                )?.setAttribute("aria-busy", "true");
                HTMLFormElement.prototype.submit.call(form);
            }
        } finally {
            delete form.dataset.confirming;
        }
    });
    window.addEventListener("pageshow", (event) => {
        if (event.persisted)
            document
                .querySelectorAll("#kategoriResults, #pengguna-results")
                .forEach((el) => el.removeAttribute("aria-busy"));
    });
})();
