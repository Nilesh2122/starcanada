<!--footer-->
		<div class="footer">
		   <p>&copy; <?php echo date('Y'); ?> STAR CANADA. All Rights Reserved</p>
		</div>
        <!--//footer-->
	</div>
	<script>
		//disable roller on number fields
		$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('wheel.disableScroll', function (e) {
			e.preventDefault()
		})
		})
		$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('wheel.disableScroll')
		})
	</script>
	<!-- Classie -->
		<script src="<?php echo base_url(); ?>assets/js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!--scrolling js-->
	<script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
   	<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"> </script>
    
   	<!-- For Desktop Notification -->
	<audio style="display:none;" id="notification">		
		<source src="<?php echo base_url(); ?>assets/audio/eventually.mp3" type="audio/mpeg">		
	</audio>
    	
    <?php //if($this->session->userdata('user_role')){ ?>
    <!--<script>
	var userrole = '<?php //echo $this->session->userdata['user_role']; ?>';
	</script>-->
   	<script src="<?php echo base_url(); ?>assets/js/notification.js"> </script>
	<?php //} ?>	

	<script src="<?php echo base_url(); ?>assets/js/sort-table.js"></script>

	<script>
	function deleteRecord(red) {
		var ask = window.confirm("Are you sure you want to delete this record?");
		if (ask) {
			window.location.href = red;
		}
	}

	function archieveRecord(red) {
		var ask = window.confirm("Are you sure you want to archive this record?");
		if (ask) {
			window.location.href = red;
		}
	}

	function unarchieveRecord(red) {
		var ask = window.confirm("Are you sure you want to unarchive this record?");
		if (ask) {
			window.location.href = red;
		}
	}
	</script>
</body>
</html>