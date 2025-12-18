
const API_URL = "http://localhost:3000/api";

let currentSearchLocationLabel = "Manila, Philippines"; 

const booking = {
    hotel: "",
    price: 0, 
    image: "",
    meta: "",
};


// Global DOM elements (Declared globally for function access)
const searchForm   = document.getElementById("hotelSearchForm");
const searchInput  = document.getElementById("hotelSearchInput");
const resultsGrid  = document.getElementById("hotelResultsGrid");
const statusText   = document.getElementById("hotelResultsStatus");
const roomsInput   = document.getElementById("roomsInput");
const adultsInput  = document.getElementById("adultsInput");
const childrenInput= document.getElementById("childrenInput");
const dateRangeInput = document.getElementById('dateRangeInput'); 
const bookingStartDateInput = document.getElementById('bookingStartDate');   
const bookingEndDateInput = document.getElementById('bookingEndDate');   
const bookingForm  = document.getElementById("bookingForm");

// Global instance variables
let bookingModalInstance = null;
let datePickerInstance = null;


 

// HELPER FUNCTIONS 
function calculateNights(start, end) {
    if (!start || !end) return 0;
    
    const date1 = new Date(start + 'T00:00:00Z');
    const date2 = new Date(end + 'T00:00:00Z');
    
    if (date1 >= date2) return 0; 
    
    const diffTime = Math.abs(date2 - date1);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    return diffDays > 0 ? diffDays : 0;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        maximumFractionDigits: 0,
    }).format(amount);
}


// CORE FUNCTIONS


function openBookingModal(btn) { 
    // 1. Get Hotel Data from the card button
    const detailsEncoded = btn.dataset.hotelDetails ?? ''; 
    
    if (!detailsEncoded || detailsEncoded === 'undefined') {
        console.error("Booking data attribute is missing or corrupted on the 'Book' button. Value:", detailsEncoded);
        alert("Error: Hotel details missing. Please try searching again.");
        return; 
    }
    
    const detailsString = decodeURIComponent(detailsEncoded); 
    let hotelData;
    try {
        hotelData = JSON.parse(detailsString);
    } catch (e) {
        console.error("Failed to parse hotel details JSON:", e);
        alert("Error processing hotel data. Data corruption issue.");
        return;
    }
    
    // 2. Get Search Criteria and Dates
    const dateRangeValue = document.getElementById('dateRangeInput').value;
    const [checkInFormatted, checkOutFormatted] = dateRangeValue.split(' - '); 
    const checkInMachine = bookingStartDateInput.value;
    const checkOutMachine = bookingEndDateInput.value;
    
    // Get Guest/Room Counts
    const rooms = parseInt(roomsInput.value) || 1;
    const adults = parseInt(adultsInput.value) || 1;
    const children = parseInt(childrenInput.value) || 0;
    
    // 3. Perform Calculations
    const nights = calculateNights(checkInMachine, checkOutMachine);
    const pricePerNight = hotelData.priceAmount || 0;
    const totalGuests = adults + children;
    const totalBookingPrice = pricePerNight * nights * rooms;
    
    // Basic date validation
    if (nights <= 0) {
        alert("Check-out date must be after check-in date. Please select a minimum 1-night stay.");
        return;
    }

    // 4. *** UPDATE MODAL DISPLAY FIELDS ***
    const confirmHotelName = document.getElementById("confirmHotelName");
    const bookingHotelImage = document.getElementById("bookingHotelImage");
    const bookingMeta = document.getElementById("bookingMeta");
    const confirmCheckInOutDates = document.getElementById("confirmCheckInOutDates");
    const confirmNights = document.getElementById("confirmNights");
    const confirmRooms = document.getElementById("confirmRooms");
    const confirmGuests = document.getElementById("confirmGuests");
    const priceBreakdownContainer = document.getElementById("priceBreakdownContainer");
    const confirmTotalPrice = document.getElementById("confirmTotalPrice");
    
    // Update Hotel Info
    if (confirmHotelName) confirmHotelName.textContent = hotelData.name;
    if (bookingHotelImage) bookingHotelImage.src = hotelData.image;
    if (bookingMeta) bookingMeta.textContent = `${hotelData.accommodation} • Rating ${hotelData.rating} • ${hotelData.reviewLabel}`;

    // Update Reservation Summary
    if (confirmCheckInOutDates) confirmCheckInOutDates.textContent = `${checkInFormatted} - ${checkOutFormatted}`;
    if (confirmNights) confirmNights.textContent = nights;
    if (confirmRooms) confirmRooms.textContent = rooms;
    if (confirmGuests) confirmGuests.textContent = `${totalGuests} total (Adults: ${adults}, Children: ${children})`;
    
// 5. Inject Price Breakdown and Total Price
const priceBreakdownHTML = `
    <div class="row g-2 small"> <div class="col-6 text-start text-muted">Price (${formatCurrency(pricePerNight)} / night) &times; ${nights} night${nights > 1 ? 's' : ''}</div>
        <div class="col-6 text-end text-muted">${formatCurrency(pricePerNight * nights)}</div>
    
        <div class="col-6 fw-semibold">Room Multiplier &times; ${rooms} room${rooms > 1 ? 's' : ''}</div>
        <div class="col-6 text-end fw-semibold">= ${formatCurrency(totalBookingPrice)}</div>
    </div>
`;

if (priceBreakdownContainer) priceBreakdownContainer.innerHTML = priceBreakdownHTML;
if (confirmTotalPrice) confirmTotalPrice.textContent = formatCurrency(totalBookingPrice);


    // 6. Set Hidden Submission Inputs
    document.getElementById("bookingHotel").value = hotelData.name;
    document.getElementById("bookingPriceValue").value = totalBookingPrice;
    document.getElementById("bookingStartDate").value = checkInMachine;
    document.getElementById("bookingEndDate").value = checkOutMachine;
    
    
    // 7. Show the modal
    if (bookingModalInstance) {
        bookingModalInstance.show();
    }
}


function createHotelCard(hotel) {
    const hotelDetails = {
        name: hotel.name || 'Unknown Hotel',
        priceAmount: hotel.priceAmount || 0,
        image: hotel.image || './images/placeholder.jpg', 
        accommodation: hotel.accommodation || 'Accommodation',
        rating: hotel.rating || 'N/A',
        reviewLabel: hotel.reviewLabel || 'No reviews',
        priceFormatted: hotel.price || "N/A",
    };
    
    let detailsEncoded = '';
    
    try {
        const jsonString = JSON.stringify(hotelDetails);
        detailsEncoded = encodeURIComponent(jsonString); 
    } catch (e) {
        console.error("Error serializing hotel data for button:", hotelDetails.name, e);
    }

    // 3. the HTML for the card
    return `
    <div class="card hotel-card-new p-0 rounded-4 shadow-sm bg-white border">
        <div class="d-flex h-100">
            <div class="hotel-card-image-wrapper flex-shrink-0" style="width:180px;">
                <img src="${hotelDetails.image}" alt="${hotelDetails.name}" class="w-100 h-100 object-fit-cover rounded-start-4" />
            </div>
            
            <div class="p-3 d-flex flex-column flex-grow-1">
                <div class="flex-grow-1">
                    <h5 class="mb-1 fw-bold text-truncate">${hotelDetails.name}</h5>
                    <p class="text-muted small mb-2">${hotelDetails.accommodation}</p>
                    
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning text-dark me-2 flex-shrink-0">
                            <i class="fas fa-star fa-fw"></i> ${hotelDetails.rating}
                        </span>
                        <small class="text-secondary text-truncate">${hotelDetails.reviewLabel}</small>
                    </div>
                </div>
                
                <div class="hotel-card-footer d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                    <div>
                        <p class="small text-muted mb-0">Price per night</p>
                        <p class="fs-5 fw-bolder text-dark mb-0">${hotelDetails.priceFormatted}</p>
                    </div>
                    
                    <a class="btn btn-sm btn-dark hotel-book-btn book-trigger"
                       href="#"
                       data-hotel-details="${detailsEncoded}">
                        Book
                    </a>
                </div>
            </div>
        </div>
    </div>`;
}


function attachCardListeners(container) {
    container.querySelectorAll(".book-trigger").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault(); 
            e.stopPropagation(); 
            openBookingModal(e.currentTarget); 
        });
    });
}


async function loadHotels(location, checkIn=null, checkOut=null, rooms=1, adults=3, children=0) {
  statusText.textContent = "Searching for hotels...";
  resultsGrid.innerHTML = "";

  let url = `${API_URL}/hotels?location=${encodeURIComponent(location)}&rooms=${rooms}&adults=${adults}&children=${children}`;
  if (checkIn) url += `&checkIn=${checkIn}`;
  if (checkOut) url += `&checkOut=${checkOut}`;

  try {
    const res = await fetch(url);
    
    // Check for HTTP errors before parsing JSON
    if (!res.ok) {
        console.error(`HTTP Error: ${res.status} from Node.js server.`);
        throw new Error(`Server returned status code ${res.status}`);
    }
    
    const data = await res.json();

    currentSearchLocationLabel = data.location;

    if (!data.hotels?.length) {
      statusText.textContent = `No hotels found for ${data.location}.`;
      return;
    }

    resultsGrid.innerHTML = data.hotels.map(createHotelCard).join("");
    attachCardListeners(resultsGrid); // This call now works

    statusText.textContent = `Showing ${data.count} stays for ${data.location}.`;

  } catch (error) {
    console.error("Fetch/Server Error:", error);
    statusText.textContent = "Error fetching hotels. Please check server connection.";
  }
}




document.addEventListener("DOMContentLoaded", function() {
    // 1. Initialize modal instance
    const bookingModalEl = document.getElementById("bookingModal");
    if (bookingModalEl) {
        bookingModalInstance = new bootstrap.Modal(bookingModalEl);
    }
    
    // Initial loading parameters
    const initialRooms = roomsInput?.value || 1;
    const initialAdults = adultsInput?.value || 3;
    const initialChildren = childrenInput?.value || 0;
    
    // 2. Initialize Litepicker and set default dates
    datePickerInstance = new Litepicker({
        element: dateRangeInput,
        singleMode: false,
        numberOfMonths: 2,
        numberOfColumns: 2,
        format: 'MMM DD, YYYY', // Display format
        tooltipText: { one: 'night', other: 'nights' },
        setup: (picker) => {
            picker.on('selected', (date1, date2) => {
                // Store machine-readable dates in hidden inputs
                bookingStartDateInput.value = date1.format('YYYY-MM-DD');
                bookingEndDateInput.value = date2.format('YYYY-MM-DD');
            });
        }
    });

    // Programmatically set initial dates to populate hidden fields
    const initialDateRange = dateRangeInput.value;
    if (initialDateRange && initialDateRange.includes(' - ')) {
        const [start, end] = initialDateRange.split(' - ');
        datePickerInstance.setDateRange(start, end);
    }
    
    // 3. Initial hotel search
    loadHotels(
        "manila",
        null,
        null,
        initialRooms,
        initialAdults,
        initialChildren
    );

    // 4. Search form listener
    searchForm?.addEventListener("submit", (e) => {
        e.preventDefault();
        const query = searchInput?.value || "manila";
        
        const currentRooms = roomsInput?.value || 1;
        const currentAdults = adultsInput?.value || 3;
        const currentChildren = childrenInput?.value || 0;
        
        loadHotels(
            query,
            null, 
            null,
            currentRooms,
            currentAdults,
            currentChildren
        );
    });
});