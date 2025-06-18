/**
 * CSRF Token Setup for AJAX Requests
 * This file sets up global CSRF token handling for all AJAX requests in the application
 */

document.addEventListener("DOMContentLoaded", function () {
    // Get the CSRF token from the meta tag
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!token) {
        console.error(
            "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
        );
        return;
    }

    // Set up Axios defaults if Axios is being used
    if (window.axios) {
        window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
    }

    // Set up jQuery AJAX defaults if jQuery is being used
    if (window.jQuery) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": token,
            },
        });
    }

    // Add event listener for AJAX errors to handle 419 errors
    if (window.jQuery) {
        $(document).ajaxError(function (event, jqXHR) {
            if (jqXHR.status === 419) {
                console.log(
                    "CSRF token mismatch. Refreshing token and page..."
                );
                // Either refresh the page or fetch a new token
                window.location.reload();
            }
        });
    }

    // Override fetch to automatically include CSRF token
    const originalFetch = window.fetch;
    window.fetch = function (url, options = {}) {
        // Only add CSRF token for same-origin requests
        const sameOrigin =
            url.startsWith("/") || url.startsWith(window.location.origin);

        if (sameOrigin && token) {
            options = options || {};
            options.headers = options.headers || {};

            // Add the CSRF token for all methods that might modify state
            if (
                !options.headers["X-CSRF-TOKEN"] &&
                (!options.method ||
                    ["POST", "PUT", "DELETE", "PATCH"].includes(
                        options.method.toUpperCase()
                    ))
            ) {
                options.headers["X-CSRF-TOKEN"] = token;
            }
        }

        return originalFetch(url, options).then((response) => {
            // If we get a 419, refresh the page to get a new token
            if (response.status === 419) {
                console.log("CSRF token expired. Refreshing page...");
                window.location.reload();
                // This won't actually execute since the page will refresh
                return Promise.reject("CSRF token mismatch");
            }
            return response;
        });
    };

    console.log("✅ CSRF token setup complete for all AJAX requests");
});

// Function to refresh CSRF token
window.refreshCsrfToken = async function () {
    try {
        const response = await fetch("/refresh-csrf", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        });

        if (response.ok) {
            const data = await response.json();

            // Update the meta tag
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute("content", data.token);
            }

            // Update any hidden _token inputs
            document
                .querySelectorAll('input[name="_token"]')
                .forEach((input) => {
                    input.value = data.token;
                });

            console.log("CSRF token refreshed successfully");
            return true;
        }
        return false;
    } catch (error) {
        console.error("Error refreshing CSRF token:", error);
        return false;
    }
};
