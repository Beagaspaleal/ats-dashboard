<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

get_header();
use App\Ats\Helpers\Table;

$candidatos = get_posts([
    'post_type' => 'candidates',
    'post_status' => 'publish',
    'numberposts' => -1,
    'fields' => 'ids'
]);
?>

<div class="ks-container" style="padding-top:8px;">
  <section class="ck-hero">
    <div class="ck-kicker">PIPELINE</div>
    <h1 class="ck-title">Acompanhar pipeline por vaga</h1>
    <p class="ck-sub">Selecione uma vaga para visualizar o funil e movimentar candidatos entre etapas.</p>

    <div class="ck-cta">
      <a class="ck-btn primary" href="/vagas">Vagas</a>
      <a class="ck-btn" href="/candidatos">Candidatos</a>
      <a class="ck-btn" href="/dashboard">Dashboard</a>
    </div>
  </section>
</div>

<div class="ks-container">
  <div class="ks-card p-3">
    <h5 class="mb-3">Selecione uma vaga</h5>
      <div class="pipeline-filter">
        <select id="job-filter">
          <option value="">Selecione uma vaga</option>
          <?php 
          $jobs = get_posts([
              'post_type' => 'jobs',
              'post_status' => 'publish',
              'numberposts' => -1
          ]);
          foreach ($jobs as $job): ?>
            <option value="<?php echo $job->ID; ?>">
              <?php echo $job->post_title; ?>
            </option>
          <?php endforeach; ?>

        </select>

      </div>
      <div id="kanban-loader" style="display:none;">
        Carregando...
      </div>
      <div id="kanban-container"></div>
  </div>
</div>

<?php
get_footer();