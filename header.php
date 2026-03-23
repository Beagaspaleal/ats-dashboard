<?php 
  $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
  $current = basename($path);

  $menu = [
    '/' => 'Home',
    '/dashboard' => 'Dashboard',
    '/vagas' => 'Vagas',
    '/candidatos' => 'Candidatos',
    '/pipeline' => 'Pipeline',
  ];
  ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
	<?php wp_head(); ?>
</head>

  <body>
    <div class="ks-shell">
      <aside class="ks-sidebar">
        <div class="ks-sidebar-brand">
	<img src="<?php echo ATS_DOMAIN . '/assets/img/Logo_Kroschu_Group.jpg';  ?>" alt="Kromberg & Schubert">
          <div>
            <strong>ATS Krochu</strong>
            <span>Recrutamento &amp; Seleção</span>
          </div>
        </div>

        <nav class="ks-sidebar-nav">
          <?php foreach ($menu as $href => $label): ?>
            <?php $active = ($current === $href) ? 'active' : ''; ?>
            <a class="<?= $active ?>" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>">
              <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </a>
          <?php endforeach; ?>
        </nav>
      </aside>

      <div class="ks-page">
        <header class="ks-topbar">
          <div>
            <div class="ks-topbar-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
            <div class="ks-topbar-subtitle">Painel interno de recrutamento</div>
          </div>

          <div class="ks-topbar-user">
            <div class="ks-user-chip">RH</div>
          </div>
        </header>

        <main class="ks-main">

<?php wp_body_open(); ?>
