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
		'page_title' => 'Candidatos',
	)
);

$candidatos = get_posts([
	'post_type' => 'candidates',
	'post_status' => 'publish',
	'numberposts' => -1,
	'fields' => 'ids'
]);
?>

<div class="ats-container">
	<section class="ats-page-header">
		<div>
			<div class="ats-page-header__eyebrow">Candidatos</div>
			<h1 class="ats-page-header__title">Gestão de candidatos</h1>
			<p class="ats-page-header__description">
				Visualize a base cadastrada no ATS e acompanhe a etapa atual de cada candidato no pipeline.
			</p>
		</div>

		<div class="ats-page-header__actions">
			<a class="ats-btn" href="#" id="btn-candidate">+ Novo candidato</a>
			<a class="ats-btn ats-btn--secondary" href="/vagas">Vagas</a>
			<a class="ats-btn ats-btn--secondary" href="/pipeline">Pipeline</a>
		</div>
	</section>

	<section class="ats-card" style="margin-top: 24px;">
		<div class="ats-card__body">
			<div class="ats-toolbar">
				<div>
					<h2 class="ats-card__title">Lista de candidatos</h2>
					<p class="ats-card__description">
						A etapa atual é sincronizada automaticamente com o pipeline.
					</p>
				</div>
			</div>

			<?php if ( ! $candidatos ) : ?>
				<div class="ats-empty-state">Não foi possível carregar candidatos.</div>
			<?php else : ?>
				<div class="ats-table-wrap">
					<table class="ats-table">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Telefone</th>
								<th>Vaga</th>
								<th>Cidade</th>
								<th>Etapa atual</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $candidatos as $value ) : ?>
								<?php $job = get_post_meta( $value, '_candidate_job', true ); ?>
								<tr>
									<td class="ats-table__cell-strong"><?php echo esc_html( get_the_title( $value ) ); ?></td>
									<td><?php echo esc_html( (string) get_post_meta( $value, '_phone', true ) ); ?></td>
									<td><?php echo esc_html( $job ? get_the_title( $job ) : '-' ); ?></td>
									<td><?php echo esc_html( (string) get_post_meta( $value, '_city', true ) ); ?></td>
									<td>
										<select class="candidate-state">
											<?php foreach ( Table::candidate_state() as $k => $v ) : ?>
												<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k, get_post_meta( $value, '_state', true ) ); ?>>
													<?php echo esc_html( $v ); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>
										<a class="ats-btn btn-save-candidate" data-id="<?php echo esc_attr( (string) $value ); ?>" href="#">
											Salvar
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</section>
</div>

<?php get_footer(); ?>
