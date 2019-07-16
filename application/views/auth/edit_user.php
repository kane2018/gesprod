<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>


    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1><?php echo lang('edit_user_heading'); ?></h1>
                <p><?php echo lang('edit_user_subheading'); ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Example</h3>
                    </div>
                    <div class="box-body">
                        <?php echo form_open(uri_string()); ?>

                        <div class="form-group">
                            <p>
                                <?php echo lang('edit_user_fname_label', 'first_name'); ?> <br />
                                <?php echo form_input($first_name, null, array('class' => 'form-control')); ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <p>
                                <?php echo lang('edit_user_lname_label', 'last_name'); ?> <br />
                                <?php echo form_input($last_name, null, array('class' => 'form-control')); ?>
                            </p>
                        </div>
                    <!--                    <p>
                        <?php echo lang('edit_user_company_label', 'company'); ?> <br />
                        <?php echo form_input($company); ?>
                                        </p>-->
                        <div class="form-group">
                            <p>
                                <?php echo lang('edit_user_phone_label', 'phone'); ?> <br />
                                <?php echo form_input($phone, null, array('class' => 'form-control')); ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <p>
                                <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                                <?php echo form_input($password, null, array('class' => 'form-control')); ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <p>
                                <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                                <?php echo form_input($password_confirm, null, array('class' => 'form-control')); ?>
                            </p>

                        </div>

                        <?php if ($this->ion_auth->is_admin()): ?>

                            <h3><?php echo lang('edit_user_groups_heading'); ?></h3>
                            <?php foreach ($groups as $group): ?>
                                <label class="checkbox">
                                    <?php
                                    $gID = $group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach ($currentGroups as $grp) {
                                        if ($gID == $grp->id) {
                                            $checked = ' checked="checked"';
                                            break;
                                        }
                                    }
                                    ?>
                                    <input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>"<?php echo $checked; ?>>
                                    <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </label>
                            <?php endforeach ?>

                        <?php endif ?>

                        <?php echo form_hidden('id', $user->id); ?>
                        <?php echo form_hidden($csrf); ?>

                        <p><?php echo form_submit('submit', lang('edit_user_submit_btn'), array('class' => 'btn btn-primary')); ?></p>

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