import Swal from "sweetalert2";
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import { Chart, BarController, DoughnutController, PieController, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from "chart.js";

Chart.register(BarController, DoughnutController, PieController, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

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
    initDashboard();
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
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
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
                info.el.classList.add('fc-event-past');
            } else {
                info.el.classList.add('fc-event-upcoming');
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
    legend.classList.add('calendar-legend');

    const upcomingLegend = document.createElement('div');
    upcomingLegend.classList.add('calendar-legend-item');
    upcomingLegend.innerHTML = `<span class="calendar-legend-dot calendar-legend-dot-upcoming"></span><span class="calendar-legend-label">Próximas citas</span>`;

    const pastLegend = document.createElement('div');
    pastLegend.classList.add('calendar-legend-item');
    pastLegend.innerHTML = `<span class="calendar-legend-dot calendar-legend-dot-past"></span><span class="calendar-legend-label">Citas pasadas</span>`;

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

const setToggleStatusButtonState = (button, status) => {
    button.classList.remove('toggle-status-btn--to-complete', 'toggle-status-btn--to-pending');

    if (status === 'completed') {
        button.textContent = 'Marcar como Pendiente';
        button.classList.add('toggle-status-btn--to-pending');
        return;
    }

    button.textContent = 'Marcar como Completada';
    button.classList.add('toggle-status-btn--to-complete');
}

const showAppointments = (appointments, date) => {
    const appointmentsContainer = document.querySelector('#appointments');
    if (!appointmentsContainer) return;

    appointmentsContainer.innerHTML = '';

    const dateObj = new Date(date + 'T00:00:00');
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = dateObj.toLocaleDateString('es-BO', options);

    const title = document.createElement('h2');
    title.classList.add('appointments-title');
    title.textContent = `Citas para el ${formattedDate}`;
    appointmentsContainer.appendChild(title);

    if (appointments.length === 0) {
        const noAppointments = document.createElement('p');
        noAppointments.classList.add('appointments-empty');
        noAppointments.textContent = 'No hay citas para esta fecha.';
        appointmentsContainer.appendChild(noAppointments);
        return;
    }

    appointments.forEach(appointment => {
        const appointmentEl = document.createElement('div');
        appointmentEl.classList.add('appointment-card');

        const time = document.createElement('p');
        time.classList.add('appointment-time');
        time.textContent = `Hora: ${appointment.time}`;
        appointmentEl.appendChild(time);

        const patient = document.createElement('p');
        patient.classList.add('appointment-patient');
        patient.textContent = `Paciente: ${appointment.patient_name}`;
        appointmentEl.appendChild(patient);

        const treatment = document.createElement('p');
        treatment.classList.add('appointment-treatment');
        treatment.textContent = `Tratamiento: ${appointment.treatment_name}`;
        appointmentEl.appendChild(treatment);

        const observations = document.createElement('p');
        observations.classList.add('appointment-observations');
        observations.textContent = `Observaciones para la cita: ${appointment.observations || 'N/A'}`;
        appointmentEl.appendChild(observations);

        const toggleStatusBtn = document.createElement('button');
        toggleStatusBtn.classList.add('toggle-status-btn');
        setToggleStatusButtonState(toggleStatusBtn, appointment.status);
        toggleStatusBtn.addEventListener('click', async () => {
            try {
                const newStatus = appointment.status === 'completed' ? 'scheduled' : 'completed';
                const url = `${location.origin}/api/appointments/update-status`;
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${appointment.id}&status=${newStatus}`
                });
                if (!response.ok) {
                    throw new Error('Error al cambiar el estado de la cita');
                }
                const result = await response.json();
                if (result.success) {
                    appointment.status = newStatus;
                    setToggleStatusButtonState(toggleStatusBtn, newStatus);

                    Swal.fire({
                        icon: 'success',
                        title: 'Estado Actualizado',
                        text: `La cita ha sido marcada como ${newStatus === 'completed' ? 'Completada' : 'Pendiente'}.`,
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.log('Error al cambiar estado: ', error);
            }
        });
        appointmentEl.appendChild(toggleStatusBtn);

        appointmentsContainer.appendChild(appointmentEl);
    });
}

// ==================== DASHBOARD ====================
const MONTH_NAMES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

const STATUS_CONFIG = {
    scheduled: { label: 'Programadas', color: 'rgb(59, 130, 246)' },
    completed: { label: 'Completadas', color: 'rgb(34, 197, 94)' },
    canceled: { label: 'Canceladas', color: 'rgb(239, 68, 68)' },
    pending: { label: 'Pendientes', color: 'rgb(245, 158, 11)' }
};

const SPECIALTY_COLORS = [
    'rgb(59, 130, 246)', 'rgb(16, 185, 129)', 'rgb(245, 158, 11)',
    'rgb(239, 68, 68)', 'rgb(139, 92, 246)', 'rgb(236, 72, 153)',
    'rgb(20, 184, 166)', 'rgb(249, 115, 22)'
];

const initDashboard = () => {
    const dashboardEl = document.querySelector('#kpi-patients');
    if (!dashboardEl) return;

    fetchKpis();
    fetchMonthlyRevenue();
    fetchAppointmentsByStatus();
    fetchTreatmentsBySpecialty();
}

const fetchKpis = async () => {
    try {
        const response = await fetch(`${location.origin}/api/dashboard/kpis`);
        if (!response.ok) throw new Error('Error al obtener KPIs');
        const data = await response.json();

        document.querySelector('#kpi-patients').textContent = data.patients_count ?? 0;
        document.querySelector('#kpi-today').textContent = data.today_appointments ?? 0;
        document.querySelector('#kpi-revenue').textContent = `Bs ${parseFloat(data.monthly_revenue ?? 0).toLocaleString('es-BO', { minimumFractionDigits: 2 })}`;
        document.querySelector('#kpi-treatments').textContent = data.active_treatments ?? 0;
    } catch (error) {
        console.log('Error KPIs:', error);
    }
}

const fetchMonthlyRevenue = async () => {
    try {
        const response = await fetch(`${location.origin}/api/dashboard/monthly-revenue`);
        if (!response.ok) throw new Error('Error al obtener ingresos mensuales');
        const data = await response.json();

        const labels = data.map(item => {
            const [year, month] = item.month.split('-');
            return `${MONTH_NAMES[parseInt(month) - 1]} ${year}`;
        });
        const values = data.map(item => parseFloat(item.total));

        const ctx = document.querySelector('#chart-revenue');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Ingresos (Bs)',
                    data: values,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => `Bs ${v}` } }
                }
            }
        });
    } catch (error) {
        console.log('Error monthly revenue:', error);
    }
}

const fetchAppointmentsByStatus = async () => {
    try {
        const response = await fetch(`${location.origin}/api/dashboard/appointments-by-status`);
        if (!response.ok) throw new Error('Error al obtener citas por estado');
        const data = await response.json();

        const labels = data.map(item => STATUS_CONFIG[item.status]?.label ?? item.status);
        const values = data.map(item => parseInt(item.count));
        const colors = data.map(item => STATUS_CONFIG[item.status]?.color ?? 'rgb(148, 163, 184)');

        const ctx = document.querySelector('#chart-appointments-status');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{ data: values, backgroundColor: colors, borderWidth: 2 }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } }
                }
            }
        });
    } catch (error) {
        console.log('Error appointments by status:', error);
    }
}

const fetchTreatmentsBySpecialty = async () => {
    try {
        const response = await fetch(`${location.origin}/api/dashboard/treatments-by-specialty`);
        if (!response.ok) throw new Error('Error al obtener tratamientos por especialidad');
        const data = await response.json();

        const labels = data.map(item => item.specialty_name);
        const values = data.map(item => parseInt(item.count));
        const colors = data.map((_, i) => SPECIALTY_COLORS[i % SPECIALTY_COLORS.length]);

        const ctx = document.querySelector('#chart-specialties');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels,
                datasets: [{ data: values, backgroundColor: colors, borderWidth: 2 }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } }
                }
            }
        });
    } catch (error) {
        console.log('Error treatments by specialty:', error);
    }
}