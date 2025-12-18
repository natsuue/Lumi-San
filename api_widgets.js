function loadWeather() {
    fetch("https://wttr.in/Manila?format=j1")
        .then((res) => {
            if (!res.ok) throw new Error("Failed to fetch weather");
            return res.json();
        })
        .then((data) => {
            const current = data.current_condition[0];
            const temp = current.temp_C;
            const desc = current.weatherDesc[0].value;
            const humidity = current.humidity;
            const wind = current.windspeedKmph;

            document.getElementById("weatherWidget").innerHTML = `
            <div class="weather-info">
                <div class="weather-temp">${temp}°C</div>
                <div class="weather-details">
                    <p><strong>${desc}</strong></p>
                    <p>💧 Humidity: ${humidity}%</p>
                    <p>💨 Wind: ${wind} km/h</p>
                </div>
            </div>
            `;
        })
        .catch(() => {
            document.getElementById("weatherWidget").innerHTML =
                "<p>Weather data unavailable</p>";
        });
}

function loadQuote() {
    fetch("https://api.quotable.io/random?tags=travel|nature|adventure")
        .then((res) => {
            if (!res.ok) throw new Error("Failed to fetch quote");
            return res.json();
        })
        .then((data) => {
            document.getElementById("quoteWidget").innerHTML = `
            <p class="quote-text">"${data.content}"</p>
            <p class="quote-author">— ${data.author}</p>
            `;
        })
        .catch(() => {
            const fallbackQuotes = [
    { text: "Not all those who wander are lost.", author: "J.R.R. Tolkien" },
    { text: "Take only memories, leave only footprints.", author: "Chief Seattle" },
    { text: "Adventure is worthwhile.", author: "Aesop" },
    { text: "Wherever you go becomes a part of you somehow.", author: "Anita Desai" },
    { text: "The journey of a thousand miles begins with a single step.", author: "Lao Tzu" },
    { text: "Travel far enough, you meet yourself.", author: "David Mitchell" },
    { text: "Jobs fill your pocket, but adventures fill your soul.", author: "Jamie Lyn Beatty" },
    { text: "To travel is to discover that everyone is wrong about other countries.", author: "Aldous Huxley" },
    { text: "The gladdest moment in human life is a departure into unknown lands.", author: "Sir Richard Burton" },
    { text: "Travel makes one modest. You see what a tiny place you occupy in the world.", author: "Gustave Flaubert" },
    { text: "Live life with no excuses, travel with no regret.", author: "Oscar Wilde" },
    { text: "The real voyage of discovery consists not in seeking new landscapes, but in having new eyes.", author: "Marcel Proust" }
];

            const q = fallbackQuotes[Math.floor(Math.random() * fallbackQuotes.length)];
            document.getElementById("quoteWidget").innerHTML = `
            <p class="quote-text">"${q.text}"</p>
            <p class="quote-author">— ${q.author}</p>
            `;
        });
}

// --- Initialization ---
document.addEventListener("DOMContentLoaded", () => {
    loadWeather();
    loadQuote();
    loadHolidays();

    // 👉 Add this part
    const nextBtn = document.getElementById("newQuoteBtn");
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            loadQuote(); // reloads a new quote when clicked
        });
    }
});


function loadHolidays() {
    fetch("https://date.nager.at/api/v3/PublicHolidays/2025/PH")
        .then((res) => {
            if (!res.ok) throw new Error("Failed to fetch holidays");
            return res.json();
        })
        .then((data) => {
            const today = new Date();
            // Show all upcoming holidays, not just 3
            const upcoming = data.filter(h => new Date(h.date) >= today);

            const holidayHTML = upcoming.map(h => {
                const d = new Date(h.date);
                const day = d.getDate();
                const month = d.toLocaleString("default", { month: "short" });

                return `
                  <div class="holiday-calendar-card shadow-sm rounded-3">
                      <div class="holiday-date">
                          <span class="holiday-day">${day}</span>
                          <span class="holiday-month">${month}</span>
                      </div>
                      <div class="holiday-info">
                          <p class="holiday-name fw-bold mb-0">${h.localName}</p>
                          <p class="holiday-type text-muted small">${h.date}</p>
                      </div>
                  </div>
                `;
            }).join("");

            document.getElementById("holidaysWidget").innerHTML = `
                <div class="holiday-calendar-scroll">
                    ${holidayHTML}
                </div>
            `;
        })
        .catch(() => {
            document.getElementById("holidaysWidget").innerHTML =
                "<p>Holiday data unavailable</p>";
        });
}



// --- Initialization ---
document.addEventListener("DOMContentLoaded", () => {
    loadWeather();
    loadQuote();
    loadHolidays();
});
