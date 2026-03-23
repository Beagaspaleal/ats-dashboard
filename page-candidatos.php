<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

use App\Ats\Helpers\Table;

get_header();

$candidatos = get_posts([
    'post_type' => 'candidates',
    'post_status' => 'publish',
    'numberposts' => -1,
    'fields' => 'ids'
]);
?>

<div class="ks-container">
  <section class="ck-hero">
    <div class="ck-kicker">CANDIDATOS</div>
    <h1 class="ck-title">Gestão de candidatos</h1>
    <p class="ck-sub">Visualize a base cadastrada no ATS e acompanhe a etapa atual de cada candidato no pipeline.</p>

    <div class="ck-cta">
      <a class="ck-btn primary" href="#" id="btn-candidate">+ Novo candidato</a>
      <a class="ck-btn" href="/vagas">Vagas</a>
      <a class="ck-btn" href="/pipeline">Pipeline</a>
    </div>
  </section>
</div>

<div class="ks-container mt-3">
  <div class="ks-card p-3">
    <div style="display:flex;justify-content:space-between;gap:12px;align-items:end;flex-wrap:wrap;margin-bottom:12px;">
      <div>
        <h3 style="margin:0;">Lista de candidatos</h3>
        <div class="text-muted" style="margin-top:4px;font-size:13px;">
          A etapa atual é sincronizada automaticamente com o pipeline.
        </div>
      </div>
    </div>

    <?php if (!$candidatos): ?>
      <div class="text-muted">Não foi possível carregar candidatos.</div>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>Telefone</th>
              <th>Vaga</th>
              <th>Cidade</th>
              <th>Etapa atual</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( $candidatos as $value ) { 
              $job = get_post_meta( $value, '_candidate_job', true); ?>
              <tr>
                <td class="fw-semibold"><? echo get_the_title( $value ); ?></td>
                <td><?php echo get_post_meta( $value, '_phone', true);?></td>
                <td><?php echo (bool) $job ? get_the_title( $job ) : '-';?></td>
                <td><?php echo get_post_meta( $value, '_city', true);?></td>
                <td>
                  <select class="candidate-state">
                    <?php foreach ( Table::candidate_state() as $k => $v ) {
                      $selected = $k == get_post_meta($value, '_state', true) ? 'selected' : '';
                    ?>
                      <option value="<?php echo $k ?>" <?php echo $selected; ?>>
                        <?php echo $v; ?>
                      </option>
                    <?php } ?>
                  </select>
                </td>
                <td><a class="ck-btn primary btn-save-candidate" data-id="<?php echo $value ?>" href="#">Salvar</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
get_footer();