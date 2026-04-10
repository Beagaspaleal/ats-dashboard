<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

use App\Ats\Helpers\Table;

get_header(
	null,
	array(
		'page_title' => 'Dashboard',
	)
);

$dash = Table::get_dashboard_data();
$topVagas = Table::get_jobs_candidature();
?>

<div class="ats-container">
	<section class="ats-page-header">
		<div>
			<div class="ats-page-header__eyebrow">Indicadores</div>
			<h1 class="ats-page-header__title">Dashboard</h1>
			<p class="ats-page-header__description">
				Visão geral do recrutamento e acompanhamento de vagas e candidatos.
			</p>
		</div>

		<div class="ats-page-header__actions">
			<a class="ats-btn" href="/vagas">Vagas</a>
			<a class="ats-btn ats-btn--secondary" href="/candidatos">Candidatos</a>
			<a class="ats-btn ats-btn--secondary" href="/pipeline">Pipeline</a>
			<a class="ats-btn ats-btn--ghost" href="/ats/export_dashboard_excel.php">Exportar</a>
		</div>
	</section>

	<section class="ats-kpi-grid">
		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $dash['jobs']['total'] ); ?></div>
			<div class="ats-kpi__label">Vagas abertas</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $dash['candidates']['total'] ); ?></div>
			<div class="ats-kpi__label">Candidatos</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) count( $dash['jobs']['states'] ) ); ?></div>
			<div class="ats-kpi__label">Etapas com volume ativo</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) count( $topVagas ) ); ?></div>
			<div class="ats-kpi__label">Vagas no ranking</div>
		</article>
	</section>

	<section class="ats-grid-2" style="margin-top: 24px;">
		<article class="ats-card">
			<div class="ats-card__body">
				<div class="ats-card__header">
					<div>
						<h2 class="ats-card__title">Funil por etapa</h2>
						<p class="ats-card__description">Distribuição das vagas por status atual.</p>
					</div>
				</div>

				<div class="ats-progress-list">
					<?php foreach ( $dash['jobs']['states'] as $key => $value ) : ?>
						<?php $pct = $dash['jobs']['total'] > 0 ? round( ( $value / $dash['jobs']['total'] ) * 100 ) : 0; ?>
						<div class="ats-progress-row">
							<div class="ats-progress-row__meta">
								<strong><?php echo esc_html( Table::jobs_state( $key ) ); ?></strong>
								<span class="is-muted"><?php echo esc_html( (string) $value ); ?></span>
							</div>
							<progress class="ats-progress" value="<?php echo esc_attr( (string) $pct ); ?>" max="100">
								<?php echo esc_html( (string) $pct ); ?>%
							</progress>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</article>

		<article class="ats-card">
			<div class="ats-card__body">
				<div class="ats-card__header">
					<div>
						<h2 class="ats-card__title">Top vagas por candidaturas</h2>
						<p class="ats-card__description">Ranking das vagas com maior volume de candidatos.</p>
					</div>
				</div>

				<?php if ( $topVagas ) : ?>
					<div class="ats-ranking-list">
						<?php foreach ( $topVagas as $vaga ) : ?>
							<div class="ats-ranking-item">
								<strong><?php echo esc_html( $vaga['title'] ?? '' ); ?></strong>
								<span class="ats-badge"><?php echo esc_html( (string) ( $vaga['candidates_count'] ?? 0 ) ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="ats-empty-state">Nenhuma vaga com candidaturas para exibir.</div>
				<?php endif; ?>
			</div>
		</article>
	</section>

	<section class="ats-card" style="margin-top: 24px;">
		<div class="ats-card__body">
			<div class="ats-card__header">
				<div>
					<h2 class="ats-card__title">Resumo por vaga</h2>
					<p class="ats-card__description">Selecione uma vaga para consultar o consolidado do funil.</p>
				</div>
			</div>

			<div class="ats-dashboard-job">
				<select id="job-dashboard-select">
					<option value="">Selecione uma vaga</option>
					<?php
					$jobs = get_posts(
						array(
							'post_type'   => 'jobs',
							'post_status' => 'publish',
							'numberposts' => -1,
						)
					);

					foreach ( $jobs as $job ) :
						?>
						<option value="<?php echo esc_attr( (string) $job->ID ); ?>">
							<?php echo esc_html( $job->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div id="job-loader" class="is-hidden">Carregando dados da vaga...</div>
				<div id="job-results"></div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
