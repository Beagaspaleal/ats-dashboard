<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

get_header(
	null,
	array(
		'page_title' => 'Pipeline',
	)
);
?>

<div class="ats-container">
	<section class="ats-page-header">
		<div>
			<div class="ats-page-header__eyebrow">Pipeline</div>
			<h1 class="ats-page-header__title">Acompanhar pipeline por vaga</h1>
			<p class="ats-page-header__description">
				Selecione uma vaga para visualizar o funil e movimentar candidatos entre etapas.
			</p>
		</div>

		<div class="ats-page-header__actions">
			<a class="ats-btn" href="/vagas">Vagas</a>
			<a class="ats-btn ats-btn--secondary" href="/candidatos">Candidatos</a>
			<a class="ats-btn ats-btn--secondary" href="/dashboard">Dashboard</a>
		</div>
	</section>

	<section class="ats-card" style="margin-top: 24px;">
		<div class="ats-card__body">
			<div class="ats-card__header">
				<div>
					<h2 class="ats-card__title">Selecione uma vaga</h2>
					<p class="ats-card__description">O kanban é carregado conforme a vaga escolhida.</p>
				</div>
			</div>

			<div class="ats-field-stack ats-pipeline-board">
				<select id="job-filter">
					<option value="">Selecione uma vaga</option>
					<?php
					$jobs = get_posts([
						'post_type' => 'jobs',
						'post_status' => 'publish',
						'numberposts' => -1
					]);

					foreach ( $jobs as $job ) :
						?>
						<option value="<?php echo esc_attr( (string) $job->ID ); ?>">
							<?php echo esc_html( $job->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div id="kanban-loader" class="is-hidden">Carregando...</div>
				<div id="kanban-container"></div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
