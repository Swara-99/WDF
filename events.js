
    // Practical 6

let events = [];
let currentPage = 1;
let recordsPerPage = 5;

let eventList = document.getElementById("eventList");

if (eventList) {

    fetch("../data/events.json")

        .then(function(response) {

            if (!response.ok) {
                throw new Error("Unable to load events");
            }

            return response.json();

        })

        .then(function(data) {

            events = data;

            displayEvents();

        })

        .catch(function(error) {

            document.getElementById("errorMessage").textContent =
                "Error loading event data.";

            console.log(error);

        });
}


// Display Events

function displayEvents() {

    let search = document.getElementById("searchEvent").value.toLowerCase();

    let category = document.getElementById("filterEvent").value;

    let sort = document.getElementById("sortEvent").value;


    // Search and Filter

    let filteredEvents = events.filter(function(event) {

        let matchesSearch =
            event.title.toLowerCase().includes(search);

        let matchesCategory =
            category == "All" || event.category == category;

        return matchesSearch && matchesCategory;

    });


    // Sorting

    if (sort == "asc") {

        filteredEvents.sort(function(a, b) {

            return new Date(a.date) - new Date(b.date);

        });

    }

    if (sort == "desc") {

        filteredEvents.sort(function(a, b) {

            return new Date(b.date) - new Date(a.date);

        });

    }


    // Pagination

    let totalPages =
        Math.ceil(filteredEvents.length / recordsPerPage);

    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }

    let start = (currentPage - 1) * recordsPerPage;

    let end = start + recordsPerPage;

    let pageEvents = filteredEvents.slice(start, end);


    // Display Events

    eventList.innerHTML = "";

    pageEvents.forEach(function(event) {

        let card = document.createElement("div");

        card.className = "event-card";

        card.innerHTML = `
            <h3>${event.title}</h3>
            <p><strong>Date:</strong> ${event.date}</p>
            <p><strong>Category:</strong> ${event.category}</p>
        `;

        eventList.appendChild(card);

    });


    if (pageEvents.length == 0) {

        eventList.innerHTML = "<p>No events found.</p>";

    }


    document.getElementById("pageNumber").textContent =
        "Page " + currentPage;

}


// Search

if (document.getElementById("searchEvent")) {

    document.getElementById("searchEvent").oninput = function() {

        currentPage = 1;

        displayEvents();

    };

}


// Filter

if (document.getElementById("filterEvent")) {

    document.getElementById("filterEvent").onchange = function() {

        currentPage = 1;

        displayEvents();

    };

}


// Sort

if (document.getElementById("sortEvent")) {

    document.getElementById("sortEvent").onchange = function() {

        currentPage = 1;

        displayEvents();

    };

}


// Previous Button

if (document.getElementById("prevButton")) {

    document.getElementById("prevButton").onclick = function() {

        if (currentPage > 1) {

            currentPage--;

            displayEvents();

        }

    };

}


// Next Button

if (document.getElementById("nextButton")) {

    document.getElementById("nextButton").onclick = function() {

        let search = document.getElementById("searchEvent").value.toLowerCase();

        let category = document.getElementById("filterEvent").value;

        let filteredEvents = events.filter(function(event) {

            let matchesSearch =
                event.title.toLowerCase().includes(search);

            let matchesCategory =
                category == "All" || event.category == category;

            return matchesSearch && matchesCategory;

        });

        let totalPages =
            Math.ceil(filteredEvents.length / recordsPerPage);

        if (currentPage < totalPages) {

            currentPage++;

            displayEvents();

        }

    };

}
