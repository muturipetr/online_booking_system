import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// vaidating input date
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('booking_date');
    const today = new Date().toISOString().split('T')[0];
    
    dateInput.setAttribute('min', today);
});

//displaying date
function updateClock() {
    const clockElement = document.getElementById('clock');
    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    clockElement.textContent = `${hours}:${minutes}:${seconds}`;
}

// Update the clock every second
setInterval(updateClock, 1000);

// Initialize the clock
updateClock();

import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
