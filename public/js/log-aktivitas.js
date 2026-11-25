(() => {
    "use strict";

    function initLogAktivitas() {
        const form = document.getElementById("logSearchForm");
        const searchInput = document.getElementById("logSearchInput");
        const results = document.getElementById("logResults");
        const resultsPanel = document.getElementById("logResultsPanel");
        const loading = document.getElementById("logLoading");
        const resetButton = document.getElementById("logReset");
        const sortInput = document.getElementById("logSort");
        const directionInput = document.getElementById("logDirection");

        const startDate = document.getElementById("tanggal_mulai");
        const endDate = document.getElementById("tanggal_akhir");

        if (
            !form ||
            !searchInput ||
            !results ||
            !resultsPanel ||
            !loading ||
            !sortInput ||
            !directionInput
        ) {
            return;
        }

        if (form.dataset.initialized === "true") {
            return;
        }

        form.dataset.initialized = "true";

        let debounceTimer = null;
        let activeController = null;
        let requestId = 0;

        function showLoading() {
            loading.hidden = false;

            resultsPanel.setAttribute("aria-busy", "true");

            results.setAttribute("inert", "");
        }

        function hideLoading() {
            loading.hidden = true;

            resultsPanel.setAttribute("aria-busy", "false");

            results.removeAttribute("inert");
        }

        function showError(message) {
            if (window.Swal && typeof window.Swal.fire === "function") {
                window.Swal.fire({
                    icon: "error",
                    title: "Gagal Memuat Data",
                    text: message,
                    confirmButtonText: "Mengerti",
                    confirmButtonColor: "#2165d5",
                });

                return;
            }

            window.alert(message);
        }

        function validateDates() {
            if (
                startDate?.value &&
                endDate?.value &&
                startDate.value > endDate.value
            ) {
                showError(
                    "Tanggal akhir tidak boleh lebih awal dari tanggal mulai.",
                );

                return false;
            }

            return true;
        }

        function buildUrl() {
            const url = new URL(form.action, window.location.origin);
            const formData = new FormData(form);

            url.search = "";

            for (const [key, value] of formData.entries()) {
                if (String(value).trim() !== "") {
                    url.searchParams.set(key, String(value).trim());
                }
            }

            url.searchParams.delete("page");

            return url;
        }

        function syncForm(url) {
            const params = url.searchParams;

            form.querySelectorAll("[name]").forEach((field) => {
                if (field.name === "sort") {
                    field.value = params.get("sort") || "created_at";

                    return;
                }

                if (field.name === "direction") {
                    field.value = params.get("direction") || "desc";

                    return;
                }

                field.value = params.get(field.name) || "";
            });
        }

        function cancelRequest() {
            requestId++;

            if (activeController) {
                activeController.abort();
                activeController = null;
            }

            hideLoading();
        }

        async function loadData(targetUrl = null, updateHistory = true) {
            clearTimeout(debounceTimer);

            if (!validateDates()) {
                cancelRequest();

                return;
            }

            const url = targetUrl
                ? new URL(targetUrl, window.location.origin)
                : buildUrl();

            if (activeController) {
                activeController.abort();
            }

            const controller = new AbortController();

            activeController = controller;

            const currentRequest = ++requestId;

            showLoading();

            const startedAt = performance.now();

            try {
                const response = await fetch(url.toString(), {
                    method: "GET",
                    credentials: "same-origin",
                    signal: controller.signal,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "text/html, application/json",
                    },
                });

                if (response.status === 422) {
                    const data = await response.json().catch(() => null);

                    const messages = Object.values(data?.errors || {}).flat();

                    throw new Error(
                        messages[0] || "Filter yang dimasukkan tidak valid.",
                    );
                }

                if (response.status === 401 || response.status === 403) {
                    throw new Error(
                        "Sesi atau hak akses Anda tidak valid. Silakan masuk kembali.",
                    );
                }

                if (!response.ok || response.redirected) {
                    throw new Error("Data aktivitas tidak berhasil dimuat.");
                }

                const html = await response.text();

                const parsed = new DOMParser().parseFromString(
                    html,
                    "text/html",
                );

                if (!parsed.querySelector(".log-table")) {
                    throw new Error(
                        "Respons server tidak sesuai. Silakan muat ulang halaman.",
                    );
                }

                const elapsed = performance.now() - startedAt;
                const minimumLoading = 300;

                if (elapsed < minimumLoading) {
                    await new Promise((resolve) => {
                        setTimeout(resolve, minimumLoading - elapsed);
                    });
                }

                if (currentRequest !== requestId || controller.signal.aborted) {
                    return;
                }

                results.innerHTML = html;

                syncForm(url);

                if (updateHistory) {
                    window.history.replaceState({}, "", url.toString());
                }
            } catch (error) {
                if (
                    error.name === "AbortError" ||
                    currentRequest !== requestId
                ) {
                    return;
                }

                syncForm(new URL(window.location.href));

                showError(
                    error.message || "Terjadi kesalahan saat memuat data.",
                );
            } finally {
                if (currentRequest === requestId) {
                    hideLoading();

                    activeController = null;
                }
            }
        }

        searchInput.addEventListener("input", () => {
            clearTimeout(debounceTimer);

            cancelRequest();

            debounceTimer = setTimeout(() => {
                loadData();
            }, 350);
        });

        form.addEventListener("submit", (event) => {
            event.preventDefault();

            loadData();
        });

        form.querySelectorAll('select, input[type="date"]').forEach((field) => {
            field.addEventListener("change", () => {
                clearTimeout(debounceTimer);

                loadData();
            });
        });

        results.addEventListener("click", (event) => {
            const sortLink = event.target.closest(".log-sort-link");

            if (sortLink) {
                event.preventDefault();

                sortInput.value = sortLink.dataset.sort;

                directionInput.value = sortLink.dataset.direction;

                loadData();

                return;
            }

            const paginationLink = event.target.closest(
                ".log-pagination-links a[href]",
            );

            if (paginationLink) {
                event.preventDefault();

                const targetUrl = new URL(
                    paginationLink.href,
                    window.location.origin,
                );

                const url = buildUrl();

                const page = targetUrl.searchParams.get("page");

                if (page) {
                    url.searchParams.set("page", page);
                }

                loadData(url);
            }
        });

        resetButton?.addEventListener("click", (event) => {
            event.preventDefault();

            clearTimeout(debounceTimer);

            form.querySelectorAll(
                'input[type="search"], input[type="date"], select',
            ).forEach((field) => {
                field.value = "";
            });

            sortInput.value = "created_at";

            directionInput.value = "desc";

            loadData();
        });

        window.addEventListener("popstate", () => {
            clearTimeout(debounceTimer);

            const url = new URL(window.location.href);

            syncForm(url);

            loadData(url, false);
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initLogAktivitas, {
            once: true,
        });
    } else {
        initLogAktivitas();
    }
})();
