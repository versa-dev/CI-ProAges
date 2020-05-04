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
?>
		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
			<!-- content ends -->
			</div><!--/#content.span10-->
		<?php } ?>
		</div><!--/fluid-row-->
		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>

		<hr>

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>

		<footer>
			<p class="pull-left">&copy; <a href="#" target="_blank"><?php echo $this->config->item('company_name') ?></a> <?php echo date('Y') ?></p>
			<!--<p class="pull-right">Powered by: Apps ISC.Ulises</p>-->
		</footer>
		<?php } ?>

	</div><!--/.fluid-container-->

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->

	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<!-- jQuery UI -->
	<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<!-- transition / effect library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<!--<script src="<?php echo base_url() ?>bootstrap/js/bootstrap-tour.js"></script>-->
	<!-- library for cookie management -->
	<script src="<?php echo base_url() ?>scripts/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src='<?php echo base_url() ?>scripts/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='<?php echo base_url() ?>scripts/jquery.dataTables.min.js'></script>

	<!-- chart libraries start -->
	<script src="<?php echo base_url() ?>scripts/excanvas.js"></script>
	<script src="<?php echo base_url() ?>scripts/jquery.flot.min.js"></script>
	<script src="<?php echo base_url() ?>scripts/jquery.flot.pie.min.js"></script>
	<script src="<?php echo base_url() ?>scripts/jquery.flot.stack.js"></script>
	<script src="<?php echo base_url() ?>scripts/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="<?php echo base_url() ?>scripts/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<!--<script src="<?php echo base_url() ?>scripts/jquery.uniform.min.js"></script>-->
	<!-- plugin for gallery image view -->
	<script src="<?php echo base_url() ?>scripts/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<!--<script src="<?php echo base_url() ?>scripts/jquery.cleditor.min.js"></script>-->
	<!-- notification plugin -->
	<script src="<?php echo base_url() ?>scripts/jquery.noty.js"></script>
	<!-- file manager library -->
	<!--<script src="<?php echo base_url() ?>scripts/jquery.elfinder.min.js"></script>-->
	<!-- star rating plugin -->
	<script src="<?php echo base_url() ?>scripts/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="<?php echo base_url() ?>scripts/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="<?php echo base_url() ?>scripts/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="<?php echo base_url() ?>scripts/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="<?php echo base_url() ?>scripts/jquery.history.js"></script>
	<!-- CDN script for implement SweetAlert -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- application script for Charisma demo -->
	<!--<script src="<?php echo base_url() ?>scripts/charisma.js"></script>-->


    <?php if( isset( $scripts ) and !empty( $scripts ) ) foreach( $scripts as $value ) echo $value; ?>



	<?php //Google Analytics code for tracking my demo site, you can remove this.
		if($_SERVER['HTTP_HOST']=='usman.it') { ?>
		<script>
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-26532312-1']);
			_gaq.push(['_trackPageview']);
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
			})();
		</script>
	<?php } ?>

</body>
</html>
