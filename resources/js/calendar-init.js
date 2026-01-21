import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

export function calendarInit() {
  const calendarWrapper = document.querySelector("#calendar");

  const base_url = $('meta[name=base_url]').attr('content');
  const get_events_url = '/admin/calendar/events';

  if (calendarWrapper) {
    // Calendar Date variable
    const newDate = new Date();
    const getDynamicMonth = () => {
      const month = newDate.getMonth() + 1;
      return month < 10 ? `0${month}` : `${month}`;
    };

    // Calendar Modal Elements
    const getModalTitleEl = document.querySelector("#event-title");
    const getModalStartDateEl = document.querySelector("#event-start-date");
    const getModalEndDateEl = document.querySelector("#event-end-date");
    const getModalAddBtnEl = document.querySelector(".btn-add-event");
    const getModalUpdateBtnEl = document.querySelector(".btn-update-event");
    const getModalHeaderEl = document.querySelector("#eventModalLabel");

    const calendarsEvents = {
      Danger: "danger",
      Success: "success",
      Primary: "primary",
      Warning: "warning",
    };

    // Calendar Elements and options
    const calendarEl = document.querySelector("#calendar");

    const calendarHeaderToolbar = {
      //left: "prev,next addEventButton",
      left: "prev,next",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    };

    // Modal Functions
    const openModal = () => {
      const modal = document.getElementById("eventModal");
      if (modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden"; // Prevent background scroll
      }
    };

    // Reset modal fields
    function resetModalFields() {
      if (getModalTitleEl) getModalTitleEl.value = "";
      if (getModalStartDateEl) getModalStartDateEl.value = "";
      if (getModalEndDateEl) getModalEndDateEl.value = "";

      const getModalIfCheckedRadioBtnEl = document.querySelector(
        'input[name="event-level"]:checked'
      );
      if (getModalIfCheckedRadioBtnEl) {
        getModalIfCheckedRadioBtnEl.checked = false;
      }
    }

    // Calendar Select function (when user clicks/drags on calendar)
    const calendarSelect = (info) => {
      resetModalFields();

      // Update modal header
      if (getModalHeaderEl) {
        getModalHeaderEl.textContent = "Add Event";
      }

      // Show Add button, hide Update button
      if (getModalAddBtnEl) getModalAddBtnEl.style.display = "flex";
      if (getModalUpdateBtnEl) getModalUpdateBtnEl.style.display = "none";

      // Set dates from selection
      if (getModalStartDateEl) getModalStartDateEl.value = info.startStr;
      if (getModalEndDateEl) {
        getModalEndDateEl.value = info.endStr || info.startStr;
      }

      openModal();
    };

    // Calendar AddEvent button click
    const calendarAddEvent = () => {
      resetModalFields();

      // Update modal header
      if (getModalHeaderEl) {
        getModalHeaderEl.textContent = "Add Event";
      }

      // Show Add button, hide Update button
      if (getModalAddBtnEl) getModalAddBtnEl.style.display = "flex";
      if (getModalUpdateBtnEl) getModalUpdateBtnEl.style.display = "none";

      // Set default start date to today
      const currentDate = new Date();
      const yyyy = currentDate.getFullYear();
      const mm = String(currentDate.getMonth() + 1).padStart(2, "0");
      const dd = String(currentDate.getDate()).padStart(2, "0");
      const combineDate = `${yyyy}-${mm}-${dd}`;

      if (getModalStartDateEl) getModalStartDateEl.value = combineDate;

      openModal();
    };

    // Calendar Event Click function (when user clicks existing event)
    const calendarEventClick = (info) => {
      const eventObj = info.event;
      const orderId = info.event.id;
      let elem =  document.querySelector('[x-data="orderModal()"]');
      let eventModal = Alpine.$data(elem);
      eventModal.openEditModal(orderId);
    };

    // Initialize Calendar
    const calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
      selectable: true,
      initialView: "dayGridMonth",
      initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
      headerToolbar: calendarHeaderToolbar,
      events: get_events_url,
      firstDay: 1,
      select: calendarSelect,
      eventClick: calendarEventClick,
      displayEventTime: true,
      displayEventEnd: false,
      customButtons: {
        addEventButton: {
          text: "Add Event +",
          click: calendarAddEvent,
        },
      },
        eventTimeFormat: {
            hour12: false,
            hour: 'numeric',
            minute: '2-digit',
            meridiem: false
        },
        slotDuration: "01:00:00",
        slotMinTime: "00:00:00",
        slotMaxTime: "24:00:00",
        expandRows: true,
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            meridiem: false
        },
        allDaySlot: false,
        nowIndicator: true,

        eventContent(eventInfo) {

        const colorClass = `fc-bg-${eventInfo.event.extendedProps.calendar.toLowerCase()}`

        return {
          html: `
            <div class="event-fc-color flex fc-event-main ${colorClass} p-1 rounded-sm">
              <div class="fc-daygrid-event-dot"></div>
              <div class="fc-event-time">${eventInfo.timeText}</div>
              <div class="fc-event-title">${eventInfo.event.title}</div>
            </div>
          `,
        }
      },
    });


    // Render Calendar
    calendar.render();

    calendarEl.addEventListener('calendar-refresh', () => {
        calendar.refetchEvents();
    });

  }

    return calendar;
}

export default calendarInit;
