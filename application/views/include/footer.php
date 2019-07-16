<div class="control-sidebar-bg"></div>
</div>
<!-- DataTables -->
<script src="<?php echo base_url() . 'assets/bower_components/datatables.net/js/jquery.dataTables.min.js' ?>"></script>
<script src="<?php echo base_url() . 'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js' ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url() . 'assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js' ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url() . 'assets/bower_components/fastclick/lib/fastclick.js' ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url() . 'assets/dist/js/adminlte.min.js' ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url() . 'assets/dist/js/demo.js' ?>"></script>

<script src="<?php echo base_url() . 'assets/plugins/iCheck/icheck.min.js' ?>"></script>

<!--<script src="<?php echo base_url() . 'assets/dist/js/pages/dashboard.js' ?>"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url() . 'assets/dist/js/demo.js' ?>"></script>

<!-- InputMask -->
<script src="<?php echo base_url() . 'assets/plugins/input-mask/jquery.inputmask.js' ?>"></script>
<script src="<?php echo base_url() . 'assets/plugins/input-mask/jquery.inputmask.date.extensions.js' ?>"></script>
<script src="<?php echo base_url() . 'assets/plugins/input-mask/jquery.inputmask.extensions.js' ?>"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url() . 'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js' ?>"></script>
<script src="<?php echo base_url() . 'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js' ?>"></script>
<script>

    $(function () {
        
        //Date picker
        $('#datepicker').datepicker({
            language: 'fr',
            format: 'dd/mm/yyyy',
            todayBtn: true,
            todayHighlight: true
        });
    });

</script>

<script>

    function rafraichir() {
        setTimeout(function () {
            location.reload();
        }, 5000);
    }

    $(function () {

        $('[data-mask]').inputmask();

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>