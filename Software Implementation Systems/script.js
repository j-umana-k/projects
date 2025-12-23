const SEAT_PRICE = 50.00;

let seats = []; // Will populate after DOMContentLoaded
const hallOptions = document.querySelectorAll(".hall-option");
const proceedBtn = document.getElementById("proceedBtn");

const showtimeHiddenInput = document.getElementById("form_showtime_id");
const totalHiddenInput = document.getElementById("total_amount_hidden");
const totalDisplay = document.getElementById("totalPriceDisplay");

// Get all showtime options
function getShowtimeOptions() {
    return document.querySelectorAll(".showtime-option");
}

// Hide showtime options and add click event
function initializeShowtimes() {
    const options = getShowtimeOptions();
    options.forEach(option => {
        option.style.display = 'none';
        option.addEventListener("click", handleShowtimeSelection);
    });
}

// When a showtime is clicked
function handleShowtimeSelection(event) {
    const option = event.currentTarget;
    const showtimeId = option.dataset.id;

    // Reload page with selected showtime and hall
    const selectedHall = document.querySelector(".hall-option.selected")?.dataset.hallId;
    const url = new URL(window.location.href);
    url.searchParams.set('showtime_id', showtimeId);
    if (selectedHall) url.searchParams.set('hall_id', selectedHall);

    window.location.href = url.toString();
}

// Update total price and proceed button state
function updateSelectedState() {
    const selectedSeats = document.querySelectorAll(".seat.selected");
    const seatIDs = [...selectedSeats].map(seat => seat.dataset.seatId || seat.getAttribute('data-seat-id'));

    // Set hidden input with selected seats
    document.getElementById("selectedSeats").value = seatIDs.join(",");

    let total = 0;
    selectedSeats.forEach(seat => {
        total += parseFloat(seat.dataset.price) || SEAT_PRICE;
    });

    // Update total display and hidden input
    totalDisplay.innerText = total.toFixed(2) + " SAR";
    totalHiddenInput.value = total.toFixed(2);

    const isShowtimeSelected = showtimeHiddenInput.value !== "";
    const hasSeats = selectedSeats.length > 0;

    // Enable proceed button only if showtime and seats selected
    proceedBtn.disabled = !(isShowtimeSelected && hasSeats);
}

// Enable card fields after selecting payment method
function enableFields() {
    const cardName = document.getElementById("cardName");
    const cardNumber = document.getElementById("cardNumber");
    const expiry = document.getElementById("expiry");
    const cvv = document.getElementById("cvv");
    const payBtn = document.querySelector(".pay-btn");

    if (cardName) cardName.disabled = false;
    if (cardNumber) cardNumber.disabled = false;
    if (expiry) expiry.disabled = false;
    if (cvv) cvv.disabled = false;
    if (payBtn) payBtn.disabled = false;
}

// Attach event to payment method radios
document.querySelectorAll("input[name='payMethod']").forEach(radio => {
    radio.addEventListener("change", enableFields);
});

// Initialize everything after DOM loaded
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const showtimeIdFromURL = params.get('showtime_id');
    const hallIdFromURL = params.get('hall_id');

    // Preselect showtime from URL if available
    if (showtimeIdFromURL) {
        showtimeHiddenInput.value = showtimeIdFromURL;

        const showtimeOption = document.querySelector(`.showtime-option[data-id='${showtimeIdFromURL}']`);
        if (showtimeOption) showtimeOption.classList.add("selected");
    }

    seats = Array.from(document.querySelectorAll(".seat"));

    initializeShowtimes();
    updateSelectedState();

    // Select hall from URL or default
    const defaultHallId = hallIdFromURL || (hallOptions[0] ? hallOptions[0].dataset.hallId : null);
    hallOptions.forEach(o => o.classList.remove("selected"));
    if (defaultHallId) {
        const defaultHallOption = document.querySelector(`.hall-option[data-hall-id='${defaultHallId}']`);
        if (defaultHallOption) defaultHallOption.classList.add("selected");
    }
// Show only showtimes for selected hall
    if (defaultHallId) {
        getShowtimeOptions().forEach(showtime => {
            showtime.style.display = (showtime.dataset.hallId === defaultHallId) ? 'block' : 'none';
        });
    }

    // Show only seats for selected hall
    seats.forEach(seat => {
        seat.style.display = (!defaultHallId || seat.dataset.hallId === defaultHallId) ? 'inline-block' : 'none';
        seat.classList.remove("selected");
    });

    // Attach click events to seats
    seats.forEach(seat => {
        seat.addEventListener("click", () => {
            if (seat.classList.contains("reserved")) return;
            seat.classList.toggle("selected");
            updateSelectedState();
        });
    });

    // Hall selection click events
    hallOptions.forEach(option => {
        option.addEventListener("click", () => {
            hallOptions.forEach(o => o.classList.remove("selected"));
            option.classList.add("selected");

            const selectedHallId = option.dataset.hallId;

            // Show only showtimes for selected hall
            getShowtimeOptions().forEach(showtime => {
                showtime.style.display = (showtime.dataset.hallId === selectedHallId) ? 'block' : 'none';
                showtime.classList.remove("selected");
            });

            // Show only seats for selected hall
            seats.forEach(seat => {
                seat.style.display = (seat.dataset.hallId === selectedHallId) ? 'inline-block' : 'none';
                seat.classList.remove("selected");
            });

            showtimeHiddenInput.value = "";
            updateSelectedState();
        });
    });

    // Payment form validation
    const paymentForm = document.getElementById("paymentForm");
    if (paymentForm) {
        paymentForm.addEventListener("submit", function(e) {
            const selectedMethod = document.querySelector("input[name='payMethod']:checked");
            const nameInput = document.getElementById("cardName").value.trim();
            const numberInput = document.getElementById("cardNumber").value.trim();
            const expiryInput = document.getElementById("expiry").value;
            const cvvInput = document.getElementById("cvv").value.trim();

            // Check payment method selection
            if (!selectedMethod) {
                e.preventDefault();
                alert("Please select a payment method.");
                return;
            }

            // Check all card fields
            if (!nameInput || !numberInput || !expiryInput || !cvvInput) {
                e.preventDefault();
                alert("Please fill all card details.");
                return;
            }

            // Store temporary booking info (optional)
            const bookingData = {
                method: selectedMethod.value,
                date: new Date().toLocaleString()
            };
            localStorage.setItem("temp_payment_info", JSON.stringify(bookingData));
        });
    }
});
