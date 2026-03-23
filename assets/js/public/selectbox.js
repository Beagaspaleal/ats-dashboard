export function initJobDashboard() {
  const select = document.getElementById("job-dashboard-select");
  const loader = document.getElementById("job-loader");
  const results = document.getElementById("job-results");

  if (!select) return;

  select.addEventListener("change", async () => {
    const jobId = select.value;

    if (!jobId) {
      results.innerHTML = "";
      return;
    }

    loader.style.display = "block";
    results.innerHTML = "";

    try {
      const response = await fetch(ajaxData.url, {
        method: "POST",
        body: new URLSearchParams({
          action: "get_job_dashboard",
          job_id: jobId,
        }),
      });

      const data = await response.json();

      if (!data.success) {
        throw new Error(data.data);
      }

      render(data.data);
    } catch (err) {
      results.innerHTML = "<p>Erro ao carregar dados</p>";
    }

    loader.style.display = "none";
  });

  function render(data) {
    let statesHtml = "";

    for (const state in data.states) {
      statesHtml += `
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <span>${formatState(state)}</span>
                <strong>${data.states[state]}</strong>
            </div>
        `;
    }

    const html = `
        <div style="gap:20px;flex-wrap:wrap;" class="ks-kpi-grid">

            <div class="ks-kpi-card">
                <div class="num" style="font-size:32px;font-weight:700;color:#1f2937;line-height:1.1;">
                    ${data.total}
                </div>
                <div class="lbl" style="margin-top:8px;font-size:14px;color:#6b7280;">
                    Candidaturas
                </div>
            </div>

            <div class="ks-kpi-card">
                <div class="lbl" style="margin-top:8px;margin-bottom:8px;font-size:14px;color:#6b7280;">
                    Etapas do pipeline
                </div>
                <div class="num" style="font-size:14px;color:#1f2937;line-height:1.4;">
                    ${statesHtml || "<span>Nenhum dado</span>"}
                </div>
            </div>

        </div>
    `;

    results.innerHTML = html;
  }
  function formatState(state) {
    const map = ajaxData.states || {};

    return map[state] || state;
  }
}
