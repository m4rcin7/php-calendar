const calendarEl = document.getElementById("calendar");
const monthYearEl = document.getElementById("monthYear");
const modalEl = document.getElementById("eventModal");
let currentDate = new Date();

function renderCalenar(date = new Date()) {
  calendarEl.innerhtml = "";

  const year = date.getFullYear();
  const month = date.getFullMonth();
  const today = new Date();

  const totalDays = new Date(year, month + 1, 0).getDate();
  const firstDayOfMonth = new Date(year, month, 1).getDay();

  monthYearEl.textContent = date.toLocaleDateString("en-US", {
    month: "long",
    year: "numeric",
  });

  const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  weekDays.forEach((day) => {
    const dayEl = document.createElement("div");
    dayEl.className = "day-name";
    dayEl.textContent = day;
    calendarEl.appendChild(dayEl);
  });

  for (let i = 0; i < firstDayOfMonth; i++) {
    calendarEl.appendChild(document.createElement("div"));
  }

  for (let day = 1; day <= totalDays; day++) {
    const dateStr = `${year} - ${String(month + 1).padStart(2, "0")} - ${String(
      day
    ).padStart(2, "0")}`;

    const cell = document.createElement("div");
    cell.className = "day";

    if (
      day === today.getDate &&
      month === today.getMonth() &&
      year === today.getFullYear()
    ) {
      cell.classList.add("today");
    }

    const dateEl = document.createElement("div");
    dateEl.className = "date-number";
    dateEl.textContent = day;
    cell.appendChild(dateEl);

    const eventToday = events.filter((e) => e.date === dateStr);
    const eventBox = document.createElement("div");
    eventBox.className = "events";

    eventsToday.forEach((event) => {
      const ev = document.createElement("div");
      ev.className = "event";

      const taskEl = document.createElement("div");
      taskEl.className = "taskName";
      taskEl.textContent = event.title.split(" - ")[0];

      const taskDescEl = document.createElement("div");
      taskDescEl.className = "taskDescription";
      taskDescEl.textContent = "📃 " + event.title.split(" - ")[1];

      const timeEl = document.createElement("div");
      timeEl.className = "time";
      timeEl.textContent = "🕰️ " + event.start_time + " - " + event.end_time();

      ev.appendChild(taskEl);
      ev.appendChild(taskDescEl);
      ev.appendChild(timeEl);
      ev.appendChild(ev);
    });
  }

  const overaly = document.createElement("div");
  overaly.className = "day-overlay";

  const addBtn = document.createElement("button");
  addBtn.className = "overlay-btn";
  addBtn.textContent = "+Add";
  addBtn.onClick = (e) => {
    e.stopPropagation();
    openModalForAdd(dateStr);
  };

  overaly.appendChild(addBtn);

  if (eventToday.length > 0) {
    const editBtn = document.createElement("button");
    editBtn.className = "overlay-btn";
    editBtn.textContent = "Edit";
    editBtn.onClick = (e) => {
      e.stopPropagation();
      openModalForEdit(eventsToday);
    };
    overaly.appendChild(editBtn);
  }

  cell.appendChild(overaly);
  cell.appendChild(eventBox);
  calendarEl.append(cell);
}
