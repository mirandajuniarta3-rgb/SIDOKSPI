(() => {
    const form = document.querySelector("#pengguna-search");
    const results = document.querySelector("#pengguna-results");
    const total = document.querySelector("#pengguna-total");
    const message = document.querySelector("#pengguna-message");
    if (!form || !results || !total || !message) return;
    let timer,
        controller,
        sequence = 0;
    const search = form.elements.search;
    function urlForSearch() {
        const url = new URL(form.action);
        url.search = new URLSearchParams(new FormData(form)).toString();
        return url;
    }
    async function load(url, push = true) {
        controller?.abort();
        controller = new AbortController();
        const current = ++sequence;
        results.setAttribute("aria-busy", "true");
        message.textContent = "Memuat data...";
        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: { Accept: "text/html" },
            });
            if (!response.ok) throw new Error("Request failed");
            const doc = new DOMParser().parseFromString(
                await response.text(),
                "text/html",
            );
            const next = doc.querySelector("#pengguna-results");
            const nextTotal = doc.querySelector("#pengguna-total");
            const nextForm = doc.querySelector("#pengguna-search");
            if (!next || !nextTotal || !nextForm) {
                location.assign(response.url);
                return;
            }
            if (current !== sequence) return;
            results.replaceChildren(...next.childNodes);
            total.textContent = nextTotal.textContent;
            for (const key of ["search", "sort", "direction"])
                form.elements[key].value = nextForm.elements[key].value;
            if (push) history.pushState(null, "", url);
            message.textContent = "";
        } catch (error) {
            if (error.name !== "AbortError" && current === sequence)
                message.textContent = "Gagal memuat data. Silakan coba lagi.";
        } finally {
            if (current === sequence) results.removeAttribute("aria-busy");
        }
    }
    search.addEventListener("input", () => {
        clearTimeout(timer);
        controller?.abort();
        ++sequence;
        timer = setTimeout(() => load(urlForSearch()), 350);
    });
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        clearTimeout(timer);
        load(urlForSearch());
    });
    results.addEventListener("click", (event) => {
        const link = event.target.closest(
            ".pengguna-sort, .pengguna-pagination a",
        );
        if (
            !link ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey ||
            event.button !== 0
        )
            return;
        event.preventDefault();
        clearTimeout(timer);
        const url = new URL(link.href);
        if ((url.searchParams.get("search") || "") !== search.value) {
            url.searchParams.set("search", search.value);
            url.searchParams.delete("page");
        }
        load(url);
    });
    window.addEventListener("popstate", () => {
        clearTimeout(timer);
        load(location.href, false);
    });
})();
