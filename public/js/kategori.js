function initKategori() {
    const form = document.getElementById("kategoriSearchForm");
    const input = document.getElementById("kategoriSearchInput");
    const results = document.getElementById("kategoriResults");
    const total = document.getElementById("kategoriTotal");

    if (!form || !input || !results) {
        console.error("Elemen halaman kategori tidak ditemukan.");
        return;
    }

    if (form.dataset.initialized === "true") {
        return;
    }

    form.dataset.initialized = "true";

    let timer = null;
    let controller = null;
    let sequence = 0;
    let saving = false;
    let pendingRequest = null;

    const originals = new WeakMap();

    function showAlert(type, message) {
        if (window.SIDFeedback?.show) {
            return window.SIDFeedback.show(type, message);
        }

        if (typeof window.Swal !== "undefined") {
            return window.Swal.fire({
                icon: type,
                title: type === "success" ? "Berhasil!" : "Gagal!",
                text: message,
                confirmButtonText: "OK",
                confirmButtonColor: "#2168d6",
            });
        }

        window.alert(message);
    }

    function setLoading(active) {
        results.setAttribute("aria-busy", active ? "true" : "false");
    }

    function initializeFields() {
        results.querySelectorAll(".kategori-inline").forEach((field) => {
            originals.set(field, field.value);

            if (field.matches(".kategori-inline-status")) {
                field.dataset.status = field.value;
            }
        });
    }

    function cancelRequest() {
        clearTimeout(timer);

        if (controller) {
            controller.abort();
            controller = null;
        }

        sequence++;
    }

    function searchUrl() {
        const url = new URL(form.action, window.location.href);
        const currentUrl = new URL(window.location.href);

        ["sort", "direction"].forEach((key) => {
            const value = currentUrl.searchParams.get(key);

            if (value) {
                url.searchParams.set(key, value);
            }
        });

        const keyword = input.value.trim();

        if (keyword) {
            url.searchParams.set("search", keyword);
        } else {
            url.searchParams.delete("search");
        }

        url.searchParams.delete("page");

        return url;
    }

    async function loadData(url, updateHistory = true) {
        if (saving) {
            pendingRequest = {
                url,
                updateHistory,
            };

            return;
        }

        cancelRequest();

        const currentSequence = sequence;
        const requestController = new AbortController();

        controller = requestController;

        setLoading(true);

        try {
            const response = await fetch(url, {
                method: "GET",
                signal: requestController.signal,
                credentials: "same-origin",
                headers: {
                    Accept: "text/html",
                },
            });

            if (!response.ok) {
                throw new Error(`Gagal memuat data (${response.status}).`);
            }

            const html = await response.text();

            const page = new DOMParser().parseFromString(html, "text/html");

            const nextResults = page.getElementById("kategoriResults");

            const nextTotal = page.getElementById("kategoriTotal");

            if (!nextResults) {
                throw new Error("Respons tabel kategori tidak ditemukan.");
            }

            if (currentSequence !== sequence) {
                return;
            }

            results.innerHTML = nextResults.innerHTML;

            if (total && nextTotal) {
                total.textContent = nextTotal.textContent;
            }

            initializeFields();

            if (updateHistory) {
                window.history.replaceState(null, "", url);
            }
        } catch (error) {
            if (error.name !== "AbortError" && currentSequence === sequence) {
                console.error(error);

                showAlert(
                    "error",
                    error.message || "Gagal memuat data kategori.",
                );
            }
        } finally {
            if (currentSequence === sequence) {
                controller = null;
                setLoading(false);
            }
        }
    }

    async function saveField(field) {
        if (!field || saving) {
            return;
        }

        const oldValue = originals.get(field);

        if (oldValue === undefined) {
            originals.set(field, field.value);
            return;
        }

        if (oldValue === field.value) {
            return;
        }

        const row = field.closest("tr[data-update-url]");

        if (!row) {
            showAlert("error", "Alamat update kategori tidak ditemukan.");

            field.value = oldValue;
            return;
        }

        const fields = Array.from(row.querySelectorAll(".kategori-inline"));

        const invalidField = fields.find((item) => !item.checkValidity());

        if (invalidField) {
            field.value = oldValue;

            showAlert(
                "error",
                invalidField.validationMessage || "Data kategori belum valid.",
            );

            return;
        }

        const token =
            row.querySelector('input[name="_token"]')?.value ||
            document.querySelector('meta[name="csrf-token"]')?.content;

        if (!token) {
            field.value = oldValue;

            showAlert(
                "error",
                "Token keamanan tidak ditemukan. Muat ulang halaman.",
            );

            return;
        }

        const payload = {};

        fields.forEach((item) => {
            payload[item.name] = item.value;
        });

        const previousValues = new Map();

        fields.forEach((item) => {
            previousValues.set(item, originals.get(item) ?? item.value);
        });

        if (timer || controller) {
            const url = searchUrl();

            pendingRequest = {
                url,
                updateHistory: true,
            };
        }

        cancelRequest();

        saving = true;

        fields.forEach((item) => {
            item.disabled = true;
        });

        setLoading(true);

        let notification = null;

        try {
            const response = await fetch(row.dataset.updateUrl, {
                method: "PUT",
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(payload),
            });

            const contentType = response.headers.get("content-type") || "";

            if (!contentType.includes("application/json")) {
                throw new Error("Server tidak mengembalikan respons JSON.");
            }

            const data = await response.json();

            if (!response.ok || data.success === false) {
                const errors = Object.values(data.errors || {})
                    .flat()
                    .join("\n");

                throw new Error(
                    errors || data.message || "Perubahan gagal disimpan.",
                );
            }

            fields.forEach((item) => {
                if (
                    data.kategori &&
                    Object.prototype.hasOwnProperty.call(
                        data.kategori,
                        item.name,
                    )
                ) {
                    item.value = data.kategori[item.name] ?? "";
                }

                originals.set(item, item.value);
            });

            notification = {
                type: "success",
                message:
                    data.message || "Perubahan kategori berhasil disimpan.",
            };
        } catch (error) {
            console.error(error);

            fields.forEach((item) => {
                item.value = previousValues.get(item);
                originals.set(item, item.value);
            });

            notification = {
                type: "error",
                message: error.message || "Perubahan kategori gagal disimpan.",
            };
        } finally {
            fields.forEach((item) => {
                item.disabled = false;

                if (item.matches(".kategori-inline-status")) {
                    item.dataset.status = item.value;
                }
            });

            saving = false;

            setLoading(false);

            if (pendingRequest) {
                const request = pendingRequest;

                pendingRequest = null;

                loadData(request.url, request.updateHistory);
            }
        }

        if (notification) {
            showAlert(notification.type, notification.message);
        }
    }

    input.addEventListener("input", () => {
        cancelRequest();

        if (!saving) {
            setLoading(true);
        }

        timer = setTimeout(() => {
            timer = null;
            loadData(searchUrl());
        }, 350);
    });

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        loadData(searchUrl());
    });

    results.addEventListener("focusin", (event) => {
        const field = event.target;

        if (!field.matches(".kategori-inline")) {
            return;
        }

        if (!originals.has(field)) {
            originals.set(field, field.value);
        }
    });

    results.addEventListener("keydown", (event) => {
        const field = event.target;

        if (!field.matches(".kategori-inline") || field.tagName === "SELECT") {
            return;
        }

        if (event.key === "Escape") {
            event.preventDefault();

            field.value = originals.get(field) ?? field.value;

            field.blur();
        }

        if (event.key === "Enter") {
            event.preventDefault();

            field.blur();
        }
    });

    results.addEventListener("focusout", (event) => {
        const field = event.target;

        if (
            field.matches("input.kategori-inline") ||
            field.matches("textarea.kategori-inline")
        ) {
            saveField(field);
        }
    });

    results.addEventListener("change", (event) => {
        const field = event.target;

        if (!field.matches("select.kategori-inline")) {
            return;
        }

        saveField(field);
    });

    results.addEventListener("click", (event) => {
        const link = event.target.closest(
            ".kategori-sort[href], .table-footer a[href]",
        );

        if (!link) {
            return;
        }

        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }

        event.preventDefault();

        const url = new URL(link.href, window.location.href);

        const keyword = input.value.trim();

        if (keyword) {
            url.searchParams.set("search", keyword);
        } else {
            url.searchParams.delete("search");
        }

        if (link.matches(".kategori-sort")) {
            url.searchParams.delete("page");
        }

        loadData(url);
    });

    window.addEventListener("popstate", () => {
        const url = new URL(window.location.href);

        input.value = url.searchParams.get("search") || "";

        loadData(url, false);
    });

    initializeFields();

    setLoading(false);

    console.info("Kategori JS berhasil diinisialisasi.");
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initKategori, { once: true });
} else {
    initKategori();
}
