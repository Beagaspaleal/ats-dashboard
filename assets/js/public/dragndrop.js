import Sortable from 'sortablejs';

export function initPipeline() {

    const select = document.getElementById('job-filter');
    const container = document.getElementById('kanban-container');
    const loader = document.getElementById('kanban-loader');

    if (!select) return;

    select.addEventListener('change', () => {
        loadKanban(select.value);
    });

    async function loadKanban(jobId) {

        if (!jobId) {
            container.innerHTML = '';
            return;
        }

        loader.style.display = 'block';
        container.innerHTML = '';

        try {

            const response = await fetch(ajaxData.url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'get_candidates_by_job',
                    job_id: jobId
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.data);
            }

            renderKanban(data.data);

        } catch (err) {
            container.innerHTML = '<p>Erro ao carregar pipeline</p>';
        }

        loader.style.display = 'none';
    }

    function renderKanban(candidates) {

        const states = ajaxData.states;

        let html = `<div class="kanban-board">`;

        for (const key in states) {

            html += `
                <div class="kanban-column">
                    <div class="kanban-header">${states[key]}</div>
                    <div class="kanban-list" data-state="${key}">
            `;

            candidates
                .filter(c => c.state === key)
                .forEach(c => {
                    html += `
                        <div class="kanban-card" data-id="${c.id}">
                            ${c.title}
                        </div>
                    `;
                });

            html += `</div></div>`;
        }

        html += `</div>`;

        container.innerHTML = html;

        initDragAndDrop();
    }

    function initDragAndDrop() {

        document.querySelectorAll('.kanban-list').forEach(list => {

            new Sortable(list, {
                group: 'kanban',
                animation: 150,

                onEnd: async (evt) => {

                    const postId = evt.item.dataset.id;
                    const newState = evt.to.dataset.state;

                    await fetch(ajaxData.url, {
                        method: 'POST',
                        body: new URLSearchParams({
                            action: 'update_candidate_state',
                            post_id: postId,
                            state: newState
                        })
                    });
                }
            });

        });
    }
}