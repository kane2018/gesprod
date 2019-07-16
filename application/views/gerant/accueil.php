<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar_gerant') ?>

<div class="content-wrapper">
   
    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1><?php echo lang('index_heading'); ?></h1>
                <p><?php echo lang('index_subheading'); ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bordered Table</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>