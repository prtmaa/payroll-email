import './bootstrap';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// Inisialisasi setelah DOM siap
document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
    });
});