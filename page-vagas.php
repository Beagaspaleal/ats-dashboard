<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

use App\Ats\Controllers\Job;
use App\Ats\Helpers\Table;

get_header();
$totals = Job::get_jobs_count_by_status();
$jobs = get_posts([
    'post_type' => 'jobs',
    'post_status' => 'publish',
    'numberposts' => -1,
    'fields' => 'ids'
]);
?>

<div class="ks-container">
  <section class="ck-hero">
    <div class="ck-kicker">GESTÃO DE VAGAS</div>
    <h1 class="ck-title">Vagas</h1>
    <p class="ck-sub">Tela para criar vagas, atualizar status e acompanhar o volume de posições abertas e fechadas.</p>

    <div class="ck-cta">
      <a class="ck-btn primary" href="#nova-vaga" id="btn-job" >+ Nova vaga</a>
      <a class="ck-btn" href="/candidatos">Candidatos</a>
      <a class="ck-btn" href="/pipeline">Pipeline</a>
    </div>

    <div class="vagas-kpi-grid">
      <div class="vagas-kpi-card">
        <div class="num"><?php echo $totals['aberta']; ?></div>
        <div class="lbl">Vagas abertas</div>
      </div>

      <div class="vagas-kpi-card">
        <div class="num"><?php echo $totals['process']; ?></div>
        <div class="lbl">Vagas em Processo</div>
      </div>

      <div class="vagas-kpi-card">
        <div class="num"><?php echo $totals['on_hold']; ?></div>
        <div class="lbl">Vagas on hold</div>
      </div>

      <div class="vagas-kpi-card">
        <div class="num"><?php echo $totals['fechada']; ?></div>
        <div class="lbl">Vagas fechadas</div>
      </div>
    </div>
  </section>
</div>

<div class="ks-container mt-3">
  <div class="ks-card p-3">
      <div style="display:flex;justify-content:space-between;gap:12px;align-items:end;flex-wrap:wrap;margin-bottom:12px;">
        <div>
          <h3 style="margin:0;">Lista de vagas</h3>
          <div class="text-muted" style="margin-top:4px;font-size:13px;">Cadastro interno, sem portal público.</div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Título</th>
              <th>Salário</th>
              <th>Status</th>
              <th>Criada em</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( $jobs as $value ) { ?>
              <tr>
                <td class="fw-semibold"><?php echo get_the_title($value); ?></td>
                <td><?php echo get_post_meta($value, '_salary', true); ?></td>
                <td>
                  <select class="job-state">
                    <?php foreach ( Table::jobs_state() as $k => $v ) {
                      $selected = $k == get_post_meta($value, '_state', true) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $k ?>" <?php echo $selected; ?>>
                        <?php echo $v; ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
                <td><?php echo get_the_date('d/m/Y H:i', $value); ?></td>
                <td>
                  <a class="ck-btn primary btn-save-job" data-id="<?php echo $value ?>" href="#">
                    Salvar
                  </a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <div class="text-muted" style="margin-top:12px;font-size:13px;">
        Total de vagas: 0
      </div>
  </div>
</div>

<?php
get_footer();