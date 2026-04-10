<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

use App\Ats\Controllers\Job;
use App\Ats\Helpers\Table;

get_header(
	null,
	array(
		'page_title' => 'Vagas',
	)
);

$totals = Job::get_jobs_count_by_status();
$jobs = get_posts([
	'post_type' => 'jobs',
	'post_status' => 'publish',
	'numberposts' => -1,
	'fields' => 'ids'
]);
?>

<div class="ats-container">
	<section class="ats-page-header">
		<div>
			<div class="ats-page-header__eyebrow">Gestão de vagas</div>
			<h1 class="ats-page-header__title">Vagas</h1>
			<p class="ats-page-header__description">
				Cadastre, acompanhe status e mantenha o processo organizado.
			</p>
		</div>

		<div class="ats-page-header__actions">
			<a class="ats-btn" href="#nova-vaga" id="btn-job">+ Nova vaga</a>
			<a class="ats-btn ats-btn--secondary" href="/candidatos">Candidatos</a>
			<a class="ats-btn ats-btn--secondary" href="/pipeline">Pipeline</a>
		</div>
	</section>

	<section class="ats-kpi-grid">
		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $totals['aberta'] ); ?></div>
			<div class="ats-kpi__label">Vagas abertas</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $totals['process'] ); ?></div>
			<div class="ats-kpi__label">Vagas em processo</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $totals['on_hold'] ); ?></div>
			<div class="ats-kpi__label">Vagas on hold</div>
		</article>

		<article class="ats-kpi">
			<div class="ats-kpi__value"><?php echo esc_html( (string) $totals['fechada'] ); ?></div>
			<div class="ats-kpi__label">Vagas fechadas</div>
		</article>
	</section>

	<section class="ats-card" style="margin-top: 24px;">
		<div class="ats-card__body">
			<div class="ats-toolbar">
				<div>
					<h2 class="ats-card__title">Lista de vagas</h2>
					<p class="ats-card__description">Cadastro interno, sem portal público.</p>
				</div>
			</div>

			<div class="ats-table-wrap">
				<table class="ats-table">
					<thead>
						<tr>
							<th>Título</th>
							<th>Salário</th>
							<th>Status</th>
							<th>Criada em</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $jobs as $value ) : ?>
							<tr>
								<td class="ats-table__cell-strong"><?php echo esc_html( get_the_title( $value ) ); ?></td>
								<td><?php echo esc_html( (string) get_post_meta( $value, '_salary', true ) ); ?></td>
								<td>
									<select class="job-state">
										<?php foreach ( Table::jobs_state() as $k => $v ) : ?>
											<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k, get_post_meta( $value, '_state', true ) ); ?>>
												<?php echo esc_html( $v ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
								<td><?php echo esc_html( get_the_date( 'd/m/Y H:i', $value ) ); ?></td>
								<td>
									<a class="ats-btn btn-save-job" data-id="<?php echo esc_attr( (string) $value ); ?>" href="#">
										Salvar
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<div class="ats-table__footer">
				Total de vagas: <?php echo esc_html( (string) count( $jobs ) ); ?>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
