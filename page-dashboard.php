<?php

/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

use App\Ats\Helpers\Table;

get_header();

$dash = Table::get_dashboard_data();
$topVagas = Table::get_jobs_candidature();
$candidaturas = '';
$maxFunil = '';
?>

<div style="position:absolute; top:20px; right:20px;">
	<a href="/ats/export_dashboard_excel.php"
		style="
        display:flex;
        align-items:center;
        justify-content:center;
        width:38px;
        height:38px;
        background:linear-gradient(135deg, #1f6f3d, #217346);
        border-radius:8px;
        text-decoration:none;
     ">
		<img src="https://img.icons8.com/color/20/microsoft-excel-2019.png">
	</a>
</div>

<div class="ck-cta">
	<a class="ck-btn primary" href="/vagas">Vagas</a>
	<a class="ck-btn" href="/lista">Candidatos</a>
	<a class="ck-btn" href="/pipeline">Pipeline</a>
</div>

<div class="ks-kpi-grid">
	<div class="ks-kpi-card">
		<div class="num" style="font-size:32px;font-weight:700;color:#1f2937;line-height:1.1;">
			<?= $dash['jobs']['total']; ?>
		</div>
		<div class="lbl" style="margin-top:8px;font-size:14px;color:#6b7280;">
			Vagas abertas
		</div>
	</div>

	<div class="ks-kpi-card">
		<div class="num" style="font-size:32px;font-weight:700;color:#1f2937;line-height:1.1;">
			<?= $dash['candidates']['total']; ?>
		</div>
		<div class="lbl" style="margin-top:8px;font-size:14px;color:#6b7280;">
			Candidatos
		</div>
	</div>

	<div class="ks-card p-3">
		<h3 style="margin:0 0 12px;">Funil por etapa</h3>
		<?php foreach ($dash['jobs']['states'] as $key => $value): ?>
			<?php $pct = $dash['jobs']['total'] > 0 ? round(($value / $dash['jobs']['total']) * 100) : 0; ?>
			<div style="margin-bottom:12px;">
				<div style="display:flex;justify-content:space-between;gap:10px;">
					<strong><?= Table::jobs_state( $key ) ?></strong>
					<span class="text-muted"><?= $value ?></span>
				</div>
				<div class="ks-stat-bar"><span style="width:<?= (int)$pct ?>%;"></span></div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="ks-card p-3">
		<h3 style="margin:0 0 12px;">Top vagas por candidaturas</h3>
		<?php foreach ($topVagas as $v): ?>
<div style="display:flex;justify-content:space-between;align-items:center;">
	
	<strong style="color:#1f2937;">
		<?= htmlspecialchars($v['title'] ?? '') ?>
	</strong>
	
	<span style="
		background:#6b4eff;
		color:#fff;
		border-radius:6px;
		padding:2px 8px;
		font-size:12px;
	">
		<?= $v['candidates_count'] ?? 0 ?>
	</span>

</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="ks-card p-3">
	<div class="job-dashboard">
		<select id="job-dashboard-select">
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
		<div id="job-loader" style="display:none;">
			Carregando dados da vaga...
		</div>
		<div id="job-results"></div>
	</div>
</div>

<?php
get_footer();
