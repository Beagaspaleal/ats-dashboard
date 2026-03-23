<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

get_header();
?>

<div class="ks-container">
  <section class="ck-hero">
    <div class="ck-kicker">ATS RECRUTAMENTO</div>
    <h1 class="ck-title">Carreiras Krochu</h1>
    <p class="ck-sub">
      Sistema desenvolvido para apoiar a gestão de vagas, candidatos e etapas do processo seletivo.
      Página principal do Kroschu Carreiras, com acesso rápido às áreas mais usadas do recrutamento.
    </p>

    <div class="ck-cta">
      <a class="ck-btn primary" href="/dashboard">Ver dashboard</a>
      <a class="ck-btn" href="/vagas">Gerenciar vagas</a>
      <a class="ck-btn" href="/candidatos">Candidatos</a>
      <a class="ck-btn" href="/pipeline">Pipeline</a>
    </div>
  </section>
</div>

<div class="ks-container mt-3">
  <div class="ks-grid ks-grid-2">
    <a class="ks-card-link" href="/vagas">
      <strong>Vagas</strong>
      <span>Crie vagas, acompanhe status e mantenha a base organizada.</span>
    </a>

    <a class="ks-card-link" href="/candidatos">
      <strong>Candidatos</strong>
      <span>Visualize candidatos cadastrados, abra perfil e acompanhe o status atual.</span>
    </a>

    <a class="ks-card-link" href="/pipeline">
      <strong>Pipeline</strong>
      <span>Acesse o kanban por vaga e mova candidatos entre as etapas do processo.</span>
    </a>

    <a class="ks-card-link" href="/dashboard">
      <strong>Dashboard</strong>
      <span>Consulte indicadores do ATS, funil e vagas com maior volume de candidaturas.</span>
    </a>
  </div>
</div>

<?php
get_footer();
