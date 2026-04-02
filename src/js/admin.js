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