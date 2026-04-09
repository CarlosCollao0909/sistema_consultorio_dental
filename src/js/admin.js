import Swal from "sweetalert2";
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";

const ALERTS_CONFIG = {
    patient_created: {
        icon: 'success',
        title: 'Paciente Registrado',
        message: 'El paciente fue creado exitosamente.'
    },
    patient_updated: {
        icon: 'success',
        title: 'Paciente Actualizado',
        message: 'Los datos del paciente fueron actualizados exitosamente.'
    },
    patient_deleted: {
        icon: 'success',
        title: 'Paciente Eliminado',
        message: 'El paciente fue eliminado exitosamente.'
    },
    specialty_created: {
        icon: 'success',
        title: 'Especialidad Registrada',
        message: 'La especialidad fue creada exitosamente.'
    },
    specialty_updated: {
        icon: 'success',
        title: 'Especialidad Actualizada',
        message: 'Los datos de la especialidad fueron actualizados exitosamente.'
    },
    specialty_deleted: {
        icon: 'success',
        title: 'Especialidad Eliminada',
        message: 'La especialidad fue eliminada exitosamente.'
    },
    treatment_created: {
        icon: 'success',
        title: 'Tratamiento Registrado',
        message: 'El tratamiento fue creado exitosamente.'
    },
    treatment_updated: {
        icon: 'success',
        title: 'Tratamiento Actualizado',
        message: 'El tratamiento fue actualizado exitosamente.'
    },
    treatment_deleted: {
        icon: 'success',
        title: 'Tratamiento Eliminado',
        message: 'El tratamiento fue eliminado exitosamente.'
    },
    appointment_created: {
        icon: 'success',
        title: 'Cita Registrada',
        message: 'La cita fue creada exitosamente.'
    },
    appointment_updated: {
        icon: 'success',
        title: 'Cita Actualizada',
        message: 'La cita fue actualizada exitosamente.'
    },
    appointment_deleted: {
        icon: 'success',
        title: 'Cita Eliminada',
        message: 'La cita fue eliminada exitosamente.'
    },
    payment_created: {
        icon: 'success',
        title: 'Pago Registrado',
        message: 'El pago fue registrado exitosamente.'
    },
    payment_deleted: {
        icon: 'success',
        title: 'Pago Eliminado',
        message: 'El pago fue eliminado exitosamente.'
    },
    attachment_uploaded: {
        icon: 'success',
        title: 'Archivo Subido',
        message: 'El archivo fue subido exitosamente.'
    },
    attachment_deleted: {
        icon: 'success',
        title: 'Archivo Eliminado',
        message: 'El archivo fue eliminado exitosamente.'
    }
};

document.addEventListener('DOMContentLoaded', () => {
    initializeAdmin();
});

const initializeAdmin = () => {
    showAlert();
    confirmDeletion();
    showCalendar();
}

const clearURLParameter = (param) => {
    const url = new URL(window.location.href);
    url.searchParams.delete(param);
    window.history.replaceState({}, document.title, url.pathname);
}

const showAlert = () => {
    const urlParam = new URLSearchParams(window.location.search);

    for (const [param, config] of Object.entries(ALERTS_CONFIG)) {
        if (urlParam.get(param)) {
            Swal.fire({
                icon: config.icon,
                title: config.title,
                text: config.message,
                confirmButtonText: 'OK'
            }).then(() => {
                clearURLParameter(param);
            })
        }
    }
}

const confirmDeletion = () => {
    const forms = document.querySelectorAll('.delete-form');

    if (forms.length === 0) return;

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}

// ==================== COLLAPSIBLE SECTIONS ====================
window.toggleSection = (sectionId) => {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById('icon-' + sectionId);

    if (section) {
        section.classList.toggle('hidden');
    }
    if (icon) {
        icon.classList.toggle('rotate-180');
    }
}

// ==================== CALENDAR ====================
const showCalendar = () => {
    const calendarEl = document.querySelector('#calendar');
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: esLocale,
        height: 'auto',
        showNonCurrentDates: false,
        fixedWeekCount: false,
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'today'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        selectable: true,
        selectMirror: true,
        unselectAuto: true,
        dayMaxEvents: 1,
        moreLinkClick: 'popover',
        moreLinkText: (number) => `+${number} más`,
        dateClick: (info) => {
            const dateSelected = encodeURIComponent(info.dateStr);
            fetchAppointmentsByDate(dateSelected);
        },
        eventDidMount: (info) => {
            const appointmentDate = new Date(info.event.start);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (appointmentDate < today) {
                info.el.classList.add('bg-gray-300', 'text-gray-600');
            } else {
                info.el.classList.add('bg-blue-500', 'text-white');
            }
        }
    });
    calendar.render();
    addLegend();

    const currentDate = new Date().toLocaleDateString('en-CA');
    fetchAppointmentsByDate(currentDate);
    fetchAppointments(calendar);
}

const addLegend = () => {
    const calendarContainer = document.querySelector('#calendar-container');
    if (!calendarContainer) return;

    if (document.querySelector('#calendar-legend')) return;

    const legend = document.createElement('div');
    legend.id = 'calendar-legend';
    legend.classList.add('mt-4', 'flex', 'items-center', 'space-x-4');

    const upcomingLegend = document.createElement('div');
    upcomingLegend.classList.add('flex', 'items-center', 'space-x-1');
    upcomingLegend.innerHTML = `<span class="w-4 h-4 bg-blue-500 rounded-full"></span><span>Próximas Citas</span>`;

    const pastLegend = document.createElement('div');
    pastLegend.classList.add('flex', 'items-center', 'space-x-1');
    pastLegend.innerHTML = `<span class="w-4 h-4 bg-gray-300 rounded-full"></span><span>Citas Pasadas</span>`;

    legend.appendChild(upcomingLegend);
    legend.appendChild(pastLegend);
    calendarContainer.appendChild(legend);
}

const fetchAppointments = async (calendar) => {
    try {
        const url = `${location.origin}/api/appointments`;
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Error al obtener las citas');
        }
        const appointments = await response.json();

        if (calendar) {
            const events = appointments.map(appointment => ({
                title: appointment.patient_name,
                start: appointment.date + 'T' + appointment.time,
                allDay: false,
            }));
            calendar.removeAllEvents();
            calendar.addEventSource(events);
            console.log('eventos agregados al calendario', events);
        }
    } catch (error) {
        console.log('Error: ', error);
    }
}

const fetchAppointmentsByDate = async (date) => {
    try {
        const url = `${location.origin}/api/appointments?date=${date}`;
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Error al obtener las citas por fecha');
        }
        const appointments = await response.json();
        showAppointments(appointments, date);
        console.log('citas obtenidas para la fecha: ', date, appointments);
    } catch (error) {
        console.log('Error date: ', error);
    }
}

const showAppointments = (appointments, date) => {
    const appointmentsContainer = document.querySelector('#appointments');
    if (!appointmentsContainer) return;

    appointmentsContainer.innerHTML = '';

    const dateObj = new Date(date);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = dateObj.toLocaleDateString('es-ES', options);

    const title = document.createElement('h2');
    title.classList.add('text-xl', 'font-semibold', 'mb-4');
    title.textContent = `Citas para ${formattedDate}`;
    appointmentsContainer.appendChild(title);

    if (appointments.length === 0) {
        const noAppointments = document.createElement('p');
        noAppointments.textContent = 'No hay citas para esta fecha.';
        appointmentsContainer.appendChild(noAppointments);
        return;
    }

    appointments.forEach(appointment => {
        const appointmentEl = document.createElement('div');
        appointmentEl.classList.add('mb-3', 'p-3', 'border', 'rounded', 'bg-gray-50');

        const time = document.createElement('p');
        time.classList.add('text-sm', 'text-gray-500');
        time.textContent = `Hora: ${appointment.time}`;
        appointmentEl.appendChild(time);

        const patient = document.createElement('p');
        patient.classList.add('text-lg', 'font-medium');
        patient.textContent = `Paciente: ${appointment.patient_name}`;
        appointmentEl.appendChild(patient);

        const treatment = document.createElement('p');
        treatment.classList.add('text-sm', 'text-gray-700');
        treatment.textContent = `Tratamiento: ${appointment.treatment_name}`;
        appointmentEl.appendChild(treatment);

        appointmentsContainer.appendChild(appointmentEl);
    });
}