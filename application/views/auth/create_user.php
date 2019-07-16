<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>


    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1><?php echo lang('create_user_heading'); ?></h1>
                <p><?php echo lang('create_user_subheading'); ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Example</h3>
                    </div>
                    <div class="box-body">
                        <?php echo form_open("user/create_user"); ?>

                        <div class="form-group">
                            <?php echo lang('create_user_fname_label', 'first_name'); ?> <br />
                            <?php echo form_input($first_name, null, array('class' => 'form-control')); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('create_user_lname_label', 'last_name'); ?> <br />
                            <?php echo form_input($last_name, null, array('class' => 'form-control')); ?>
                        </div>

                        <?php
                        if ($identity_column !== 'email') {
                            echo '<p>';
                            echo lang('create_user_identity_label', 'identity');
                            echo '<br />';
                            echo form_error('identity');
                            echo form_input($identity);
                            echo '</p>';
                        }
                        ?>

<!--      <p>
                        <?php echo lang('create_user_company_label', 'company'); ?> <br />
                        <?php echo form_input($company); ?>
      </p>-->

                        <div class="form-group">
                            <?php echo lang('create_user_email_label', 'email'); ?> <br />
                            <?php echo form_input($email, null, array('class' => 'form-control')); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                            <?php echo form_input($phone, null, array('class' => 'form-control', 'data-inputmask' => '\'mask\': \'99 999 99 99\'', 'data-mask' => '')); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('create_user_password_label', 'password'); ?> <br />
                            <?php echo form_input($password, null, array('class' => 'form-control')); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
                            <?php echo form_input($password_confirm, null, array('class' => 'form-control')); ?>
                        </div>


                        <div class="box-footer"><?php echo form_submit('submit', lang('create_user_submit_btn'), array('class' => 'btn btn-primary')); ?></div>

                        <?php echo form_close(); ?>

                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->    

<?php $this->load->view('include/pied') ?>