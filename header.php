<?php
$request_path  = parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
$current_path  = is_string( $request_path ) && '' !== $request_path ? untrailingslashit( $request_path ) : '/';
$page_title    = isset( $args['page_title'] ) ? (string) $args['page_title'] : wp_get_document_title();
$page_subtitle = isset( $args['page_subtitle'] ) ? (string) $args['page_subtitle'] : 'Painel interno de recrutamento';

$menu = array(
	'/'           => 'Home',
	'/dashboard'  => 'Dashboard',
	'/vagas'      => 'Vagas',
	'/candidatos' => 'Candidatos',
	'/pipeline'   => 'Pipeline',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="ats-layout">
	<aside class="ats-sidebar">
		<div class="ats-sidebar__brand">
			<img src="<?php echo esc_url( ATS_DOMAIN . '/assets/img/Logo_Kroschu_Group.jpg' ); ?>" alt="Kromberg &amp; Schubert">
			<div>
				<div class="ats-sidebar__title">ATS Krochu</div>
				<div class="ats-sidebar__subtitle">Recrutamento &amp; Seleção</div>
			</div>
		</div>

		<nav class="ats-sidebar__nav" aria-label="Navegação principal">
			<?php foreach ( $menu as $href => $label ) : ?>
				<?php $is_active = untrailingslashit( $current_path ) === untrailingslashit( $href ) ? ' is-active' : ''; ?>
				<a class="ats-sidebar__link<?php echo esc_attr( $is_active ); ?>" href="<?php echo esc_url( home_url( $href ) ); ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
	</aside>

	<div class="ats-layout__content">
		<header class="ats-topbar">
			<div>
				<div class="ats-topbar__title"><?php echo esc_html( $page_title ); ?></div>
				<div class="ats-topbar__subtitle"><?php echo esc_html( $page_subtitle ); ?></div>
			</div>

			<div class="ats-topbar__chip">RH</div>
		</header>

		<main class="ats-main">
