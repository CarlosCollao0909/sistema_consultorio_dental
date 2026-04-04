import Swal from "sweetalert2";

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

// ==================== MODAL FUNCTIONS ====================

window.openModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

window.closeModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// ==================== TREATMENT MODAL ====================

window.openEditTreatmentModal = (button) => {
    const form = document.getElementById('treatment-form');
    const title = document.getElementById('treatment-modal-title');

    form.action = '/admin/patients/profile/treatment-update';
    title.textContent = 'Editar Tratamiento';

    document.getElementById('treatment-id').value = button.dataset.id;
    document.getElementById('treatment-treatment_name').value = button.dataset.treatmentName;
    document.getElementById('treatment-user_id').value = button.dataset.userId;
    document.getElementById('treatment-specialty_id').value = button.dataset.specialtyId;
    document.getElementById('treatment-total_cost').value = button.dataset.totalCost;
    document.getElementById('treatment-status').value = button.dataset.status;

    // Open modal directly without triggering reset
    const modal = document.getElementById('treatment-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Reset treatment modal to create mode when opening fresh
const originalOpenModal = window.openModal;
window.openModal = (modalId) => {
    if (modalId === 'treatment-modal') {
        const form = document.getElementById('treatment-form');
        const title = document.getElementById('treatment-modal-title');
        if (form && title) {
            form.action = '/admin/patients/profile/treatment-create';
            title.textContent = 'Nuevo Tratamiento';
            document.getElementById('treatment-id').value = '';
            form.reset();
        }
    }
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

// ==================== APPOINTMENT MODAL ====================

window.openAppointmentModal = (treatmentId) => {
    const form = document.getElementById('appointment-form');
    const title = document.getElementById('appointment-modal-title');

    form.action = '/admin/patients/profile/appointment-create';
    title.textContent = 'Nueva Cita';
    document.getElementById('appointment-id').value = '';
    document.getElementById('appointment-treatment_id').value = treatmentId;
    document.getElementById('appointment-date').value = '';
    document.getElementById('appointment-time').value = '';
    document.getElementById('appointment-status').value = 'programada';
    document.getElementById('appointment-observations').value = '';

    const modal = document.getElementById('appointment-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

window.openEditAppointmentModal = (button) => {
    const form = document.getElementById('appointment-form');
    const title = document.getElementById('appointment-modal-title');

    form.action = '/admin/patients/profile/appointment-update';
    title.textContent = 'Editar Cita';

    document.getElementById('appointment-id').value = button.dataset.id;
    document.getElementById('appointment-treatment_id').value = button.dataset.treatmentId;
    document.getElementById('appointment-date').value = button.dataset.date;
    document.getElementById('appointment-time').value = button.dataset.time;
    document.getElementById('appointment-status').value = button.dataset.status;
    document.getElementById('appointment-observations').value = button.dataset.observations || '';

    const modal = document.getElementById('appointment-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// ==================== PAYMENT MODAL ====================

window.openPaymentModal = (treatmentId, balance) => {
    document.getElementById('payment-treatment_id').value = treatmentId;
    document.getElementById('payment-balance-display').textContent = '$' + balance.toFixed(2);
    document.getElementById('payment-amount_paid').value = '';
    document.getElementById('payment-amount_paid').max = balance;

    const modal = document.getElementById('payment-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
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