<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-2 col-md-8">

                <h1><?php echo lang('edit_group_heading'); ?></h1>
                <p><?php echo lang('edit_group_subheading'); ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Désactivation</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <?php echo form_open(current_url()); ?>

                        <p>
                            <?php echo lang('edit_group_name_label', 'group_name'); ?> <br />
                            <?php echo form_input($group_name, null, array('class' => 'form-control')); ?>
                        </p>

                        <p>
                            <?php echo lang('edit_group_desc_label', 'description'); ?> <br />
                            <?php echo form_input($group_description, null, array('class' => 'form-control')); ?>
                        </p>

                        <p><?php echo form_submit('submit', lang('edit_group_submit_btn'), array('class' => 'btn btn-primary')); ?></p>

                        <?php echo form_close(); ?>

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