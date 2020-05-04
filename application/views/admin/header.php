<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/*

  Author		Ulises Rodríguez
  Site:			http://www.ulisesrodriguez.com
  Twitter:		https://twitter.com/#!/isc_ulises
  Facebook:		http://www.facebook.com/ISC.Ulises
  Github:		https://github.com/ulisesrodriguez
  Email:		ing.ulisesrodriguez@gmail.com
  Skype:		systemonlinesoftware
  Location:		Guadalajara Jalisco Mexíco


*/
  if (isset($user) && isset($user['picture']))
  {
  	$this->load->helper( 'user_image' );
  	get_default_user_image( $user['picture'] );
  }
  $segments = $this->uri->rsegment_array();
  if (($this->roles_vs_access !== FALSE) &&
  	(isset($segments[1])) &&
  	($segments[1] == 'agent')
  )
  {
  	$hide_menu = FALSE;
  	foreach ($this->roles_vs_access as $access)
  	{
  		if (($access['module_name'] == 'Agent Profile') && ($access['action_name'] == 'Ocultar el menú'))
  			$hide_menu = TRUE;
  	}
  }
  else
  	$hide_menu = FALSE;

  if ($proages_home = $this->session->userdata('proages_home'))
  	$home = site_url($proages_home);
  else
  	$home = base_url();
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>

  	<meta charset="utf-8">
  	<title>Pro-ages <?php if( isset( $title ) ) echo $title ?></title>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta name="description" content="">
  	<meta name="author" content="Ulises Rodriguez">

  	<!-- The styles -->
  	<link id="bs-css" href="<?php echo base_url() ?>style/bootstrap-cerulean.css" rel="stylesheet">
  	<style type="text/css">
  	body {
  		padding-bottom: 40px;
  	}
  	.sidebar-nav {
  		padding: 9px 0;
  	}
  </style>
  <link href="<?php echo base_url() ?>bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>bootstrap/FortAwesome/css/font-awesome.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>style/charisma-app.css" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <link href="<?php echo base_url() ?>bootstrap/Ui/css/custom-theme/jquery-ui-1.10.0.custom.css" rel="stylesheet">
  <link href='<?php echo base_url() ?>style/fullcalendar.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/fullcalendar.print.css' rel='stylesheet'  media='print'>
  <link href='<?php echo base_url() ?>style/chosen.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/uniform.default.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/colorbox.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/jquery.cleditor.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/jquery.noty.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/noty_theme_default.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/elfinder.min.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/elfinder.theme.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/jquery.iphone.toggle.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/opa-icons.css' rel='stylesheet'>
  <link href='<?php echo base_url() ?>style/uploadify.css' rel='stylesheet'>
  <link href="<?php echo base_url() ?>style/style.css" rel="stylesheet">
  <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<!--<link rel="shortcut icon" href="img/favicon.ico">-->

	<?php if( isset( $css ) and !empty( $css ) ) foreach( $css as $value ) echo $value; ?>


</head>

<body>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push(

{'gtm.start': new Date().getTime(),event:'gtm.js'}
);var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TWJ55N6');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TWJ55N6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<?php if (!$hide_menu): ?>
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
				<?php endif; ?>
				<a class="brand" href="<?php echo $home; ?>"> <img alt="Charisma Logo" src="<?php echo base_url() . 'images/' . $this->config->item('logo') ?>" /> <span><?php echo $this->config->item('company_name') ?></span></a>

				<!-- theme selector starts -->
				<div class="btn-group pull-right theme-container" >
					<!--<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-tint"></i><span class="hidden-phone"> Change Theme / Skin</span>
						<span class="caret"></span>
					</a>-->
					<ul class="dropdown-menu" id="themes">
						<!--<li><a data-value="classic" href="#"><i class="icon-blank"></i> Classic</a></li>
						<li><a data-value="cerulean" href="#"><i class="icon-blank"></i> Cerulean</a></li>
						<li><a data-value="cyborg" href="#"><i class="icon-blank"></i> Cyborg</a></li>
						<li><a data-value="redy" href="#"><i class="icon-blank"></i> Redy</a></li>
						<li><a data-value="journal" href="#"><i class="icon-blank"></i> Journal</a></li>
						<li><a data-value="simplex" href="#"><i class="icon-blank"></i> Simplex</a></li>
						<li><a data-value="slate" href="#"><i class="icon-blank"></i> Slate</a></li>
						<li><a data-value="spacelab" href="#"><i class="icon-blank"></i> Spacelab</a></li>
						<li><a data-value="united" href="#"><i class="icon-blank"></i> United</a></li>-->
					</ul>
				</div>
				<!-- theme selector ends -->

				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript: void(0);">
						<img src="<?php echo base_url() . 'usuarios/assets/profiles/' . $user['picture'] ?>" />
						<span class="hidden-phone"> <?php echo $user['name'] ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo base_url() ?>usuarios/editar_perfil/<?php echo $user['id']; ?>.html">Editar perfil</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url() ?>usuarios/logout.html">Logout</a></li>
					</ul>
				</div>

				<!-- user dropdown ends -->

				<div class="top-nav nav-collapse">
					<!--<ul class="nav">
						<li><a href="#">Visit Site</a></li>
						<li>
							<form class="navbar-search pull-left">
								<input placeholder="Search" class="search-query span2" name="query" type="text">
							</form>
						</li>
					</ul>-->
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<?php } ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<?php if(!isset($no_visible_elements) || !$no_visible_elements) { ?>
			<?php if (!$hide_menu): ?>
				<!-- left menu starts -->
				<div class="span2 main-menu-span">
					<div class="well nav-collapse sidebar-nav">
						<ul class="nav nav-tabs nav-stacked main-menu">
							<li class="nav-header hidden-tablet">Navegación</li>

							<li><a class="ajax-link" href="<?php echo $home ?>"><i class="icon-home"></i><span class="hidden-tablet"><?php echo $this->config->item('company_name') ?></span></a></li>

							<?php
							/**
							 *	Check $roles_vs_access for setting or added navigation for an user
							 **/
							if( !empty( $roles_vs_access ) ): ?>
							<?php foreach( $roles_vs_access  as $value ): if( in_array( 'Modulos', $value ) ): ?>
								<li><a href="<?php echo base_url() ?>modulos.html"><i class="icon-th"></i><span class="hidden-tablet">Módulos</span></a></li>
								<?php break; endif; endforeach; ?>

								<?php foreach( $roles_vs_access  as $value ): if( in_array( 'Rol', $value ) ): ?>
									<li><a href="<?php echo base_url() ?>roles.html"><i class="icon-th"></i><span class="hidden-tablet">Rol</span></a></li>
									<?php break; endif; endforeach; ?>

									<?php foreach( $roles_vs_access  as $value ): if( in_array( 'Usuarios', $value ) ): ?>
										<li><a href="<?php echo base_url() ?>usuarios.html"><i class="icon-th"></i><span class="hidden-tablet">Usuarios</span></a></li>
										<?php break; endif; endforeach; ?>

										<?php foreach( $roles_vs_access  as $value ): if( in_array( 'Grupos', $value ) ): ?>
											<li><a href="<?php echo base_url() ?>groups.html"><i class="icon-user"></i><span class="hidden-tablet">Grupos</span></a></li>
											<?php break; endif; endforeach; ?>
                        <?php foreach( $roles_vs_access  as $value ): if( in_array( 'Orden de trabajo', $value ) ): ?>
                        <li><a href="<?php echo base_url() ?>ot.html"><i class="icon-tablet"></i><span class="hidden-tablet">Orden trabajo</span></a></li>
                        <?php break; endif; endforeach; ?>
                    <?php if( isset($user) && isset($user['id'])):
                    foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Operations', $value ) ): ?>
                    	<li><a href="<?php echo base_url() ?>operations/index/<?php echo $user['id'] ?>.html"><i class="icon-briefcase"></i><span class="hidden-tablet">Operaciones</span></a></li>
                    	<?php break; endif;
                    endforeach;
                    endif; ?>

                    <?php
                    foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Orden de trabajo', $value ) && ( $value['action_name'] == 'Importar payments' )): ?>
                    	<li><a href="<?php echo base_url() ?>ot/import_payments.html"><i class="icon-hdd"></i><span class="hidden-tablet">Importar Pagos</span></a></li>
                    	<?php break; endif; endforeach;?>

                    	<?php foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Orden de trabajo', $value ) && ( $value['action_name'] == 'Ver reporte' )): ?>
                    	<li><a href="<?php echo base_url() ?>director"><i class="icon-file"></i><span class="hidden-tablet">Reporte directivo</span></a></li>
                    	<?php break; endif; endforeach; ?>

                    	<?php foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Solicitudes', $value ) && ( $value['action_name'] == 'Ver reporte' )): ?>
                    	<li><a href="<?php echo base_url() ?>solicitudes"><i class="icon-file"></i><span class="hidden-tablet">Reporte de solicitudes</span></a></li>
                    	<?php break; endif; endforeach; ?>

                    	<?php foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Reporte de produccion', $value ) && ( $value['action_name'] == 'Ver reporte' )): ?>
                    	<li><a href="<?php echo base_url() ?>rpventas"><i class="icon-file"></i><span class="hidden-tablet">Reporte de venta anual</span></a></li>
						<?php break; endif; endforeach; ?>
						
						<li><a href="<?php echo base_url() ?>termometro"><i class="icon-hdd"></i><span class="hidden-tablet">Termómetro de Ventas        <span class="label label-important">Nuevo</span></span></a></li>
						
                    	<?php foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Actividades', $value ) && ( $value['action_name'] == 'Ver' )): ?>
                    	<li><a href="<?php echo base_url() ?>activities.html"><i class="icon-tasks"></i><span class="hidden-tablet">Mis actividades</span></a></li>
                    	<?php break; endif; endforeach; ?>

                    	<?php foreach( $roles_vs_access  as $value ):
                    	if( in_array( 'Actividades', $value ) &&  ( $value['action_name'] == 'Actividades de ventas' )): ?>
                    	<li><a href="<?php echo base_url() ?>activities/sales_activities_stats.html"><i class="icon-tasks"></i><span class="hidden-tablet">Actividades de ventas</span></a></li>
                    	<?php break; endif; endforeach; ?>

                    	<?php foreach( $roles_vs_access as $value ):
                    	if( in_array( 'Settings', $value ) && ( $value['action_name'] == 'Ver' )): ?>
                    	<li><a href="<?php echo base_url() ?>settings.html"><i class="icon-cog"></i><span class="hidden-tablet">Configuración</span></a></li>
                    	<?php break; endif; endforeach; ?>
                    <?php endif; ?>

						<!--
                        <li><a class="ajax-link" href="form.html"><i class="icon-edit"></i><span class="hidden-tablet"> Forms</span></a></li>
						<li><a class="ajax-link" href="chart.html"><i class="icon-list-alt"></i><span class="hidden-tablet"> Charts</span></a></li>
						<li><a class="ajax-link" href="typography.html"><i class="icon-font"></i><span class="hidden-tablet"> Typography</span></a></li>
						<li><a class="ajax-link" href="gallery.html"><i class="icon-picture"></i><span class="hidden-tablet"> Gallery</span></a></li>
						<li class="nav-header hidden-tablet">Sample Section</li>
						<li><a class="ajax-link" href="table.html"><i class="icon-align-justify"></i><span class="hidden-tablet"> Tables</span></a></li>
						<li><a class="ajax-link" href="calendar.html"><i class="icon-calendar"></i><span class="hidden-tablet"> Calendar</span></a></li>
						<li><a class="ajax-link" href="grid.html"><i class="icon-th"></i><span class="hidden-tablet"> Grid</span></a></li>
						<li><a class="ajax-link" href="file-manager.html"><i class="icon-folder-open"></i><span class="hidden-tablet"> File Manager</span></a></li>
						<li><a href="tour.html"><i class="icon-globe"></i><span class="hidden-tablet"> Tour</span></a></li>
						<li><a class="ajax-link" href="icon.html"><i class="icon-star"></i><span class="hidden-tablet"> Icons</span></a></li>
						<li><a href="error.html"><i class="icon-ban-circle"></i><span class="hidden-tablet"> Error Page</span></a></li>
						<li><a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> Login Page</span></a></li>-->
					</ul>
					<!--<label id="for-is-ajax" class="hidden-tablet" for="is-ajax"><input id="is-ajax" type="checkbox"> Ajax on menu</label>-->
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
		<?php endif; ?>

		<div id="content" class="span<?php if ($hide_menu) echo 12; else echo 10; ?>">
			<!-- content starts -->
			<?php } ?>
