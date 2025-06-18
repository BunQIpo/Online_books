// Load Bootstrap JavaScript (modals, dropdowns, etc.)
import "bootstrap";

// Load Font Awesome icons
import "@fortawesome/fontawesome-free/css/all.min.css";

// Import CSRF setup for AJAX requests
import "./csrf-setup";

// Import AOS (Animate On Scroll)
import AOS from "aos";
import "aos/dist/aos.css";

// Initialize AOS
document.addEventListener("DOMContentLoaded", function () {
    AOS.init({
        duration: 800,
        easing: "ease-in-out",
        once: true,
        mirror: false,
    });
});

// Vue setup (keep your existing Vue config)
import "./bootstrap";
import { createApp } from "vue";

const app = createApp({});

import ExampleComponent from "./components/ExampleComponent.vue";
app.component("example-component", ExampleComponent);

app.mount("#app");
