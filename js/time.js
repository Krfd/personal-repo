// Date and Time
const date = document.querySelector("#date");
const time = document.querySelector("#time");

setInterval(() => {
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };

    const today = new Date();

    date.innerHTML = today.toLocaleDateString("en-US", options);
    time.innerHTML = today.toLocaleTimeString("en-US");
}, 1000);
