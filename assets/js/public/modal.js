import Swal from 'sweetalert2';

export function openJobModal() {
    Swal.fire({
        title: 'Cadastro de Vaga',
        html: `
            <input id="title" class="swal2-input" placeholder="Título">
            <input id="salary" class="swal2-input" placeholder="Salário">
            <input id="benefits" class="swal2-input" placeholder="Benefícios">
            <input id="city" class="swal2-input" placeholder="Cidade">
            <textarea id="description" class="swal2-textarea" placeholder="Descrição"></textarea>
        `,
        confirmButtonText: 'Cadastrar',
        preConfirm: () => {

            const data = {
                title: document.getElementById('title').value,
                salary: document.getElementById('salary').value,
                benefits: document.getElementById('benefits').value,
                city: document.getElementById('city').value,
                description: document.getElementById('description').value,
            };

            if (Object.values(data).some(v => !v)) {
                Swal.showValidationMessage('Preencha todos os campos');
                return false;
            }

            return fetch(ajaxData.url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'create_job',
                    ...data
                })
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    throw new Error(res.data);
                }
                return res;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire('Sucesso!', 'Vaga cadastrada.', 'success');
        }
    });
}

export function openCandidateModal() {

    Swal.fire({
        title: 'Cadastro de Candidato',
        html: `
            <input id="name" class="swal2-input" placeholder="Nome">
            <input id="phone" class="swal2-input" placeholder="Telefone">
            <input id="city" class="swal2-input" placeholder="Cidade">
            <input id="resume" type="file" class="swal2-file">
        `,
        confirmButtonText: 'Cadastrar',
        preConfirm: () => {

            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const file = document.getElementById('resume').files[0];
            const city = document.getElementById('city').value;

            if (!name || !phone || !file) {
                Swal.showValidationMessage('Todos os campos são obrigatórios');
                return false;
            }

            const formData = new FormData();
            formData.append('action', 'create_candidate');
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('resume', file);
            formData.append('city', city);

            return fetch(ajaxData.url, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    throw new Error(res.data);
                }
                return res;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
            });
        }
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire('Sucesso!', 'Candidato cadastrado.', 'success');
        }
    });
}

export function initJobStateUpdate() {

    document.addEventListener('click', async (e) => {

        const button = e.target.closest('.btn-save-job');

        if (!button) return;

        e.preventDefault();

        const postId = button.dataset.id;

        // pega o select da mesma linha
        const row = button.closest('tr');
        const select = row.querySelector('.job-state');

        const state = select.value;

        try {

            const response = await fetch(ajaxData.url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'update_job_state',
                    post_id: postId,
                    state: state
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.data);
            }

            // sucesso → recarrega
            window.location.reload();

        } catch (error) {
            alert(error.message || 'Erro ao salvar');
        }

    });
}

export function initCandidateStateUpdate() {

    document.addEventListener('click', async (e) => {

        const button = e.target.closest('.btn-save-candidate');

        if (!button) return;

        e.preventDefault();

        const postId = button.dataset.id;

        // pega o select da mesma linha
        const row = button.closest('tr');
        const select = row.querySelector('.candidate-state');

        const state = select.value;

        try {

            const response = await fetch(ajaxData.url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'update_candidate_state',
                    post_id: postId,
                    state: state
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.data);
            }

            // sucesso → recarrega
            window.location.reload();

        } catch (error) {
            alert(error.message || 'Erro ao salvar');
        }

    });
}