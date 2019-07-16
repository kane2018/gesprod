<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

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

                        <table class="table table-bordered">
                            <tr>
                                <th><?php echo lang('index_fname_th'); ?></th>
                                <th><?php echo lang('index_lname_th'); ?></th>
                                <th><?php echo lang('index_email_th'); ?></th>
                                <th><?php echo lang('index_groups_th'); ?></th>
                                <th><?php echo lang('index_status_th'); ?></th>
                                <th><?php echo lang('index_action_th'); ?></th>
                            </tr>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php foreach ($user->groups as $group): ?>
                                            <?php echo anchor("user/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')); ?><br />
                                        <?php endforeach ?>
                                    </td>
                                    <td><?php echo ($user->active) ? anchor("user/deactivate/" . $user->id, lang('index_active_link')) : anchor("user/activate/" . $user->id, lang('index_inactive_link')); ?></td>
                                    <td><?php echo anchor("user/edit_user/" . $user->id, 'Edit'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('user/create_user', lang('index_create_user_link')) ?> | <?php echo anchor('user/create_group', lang('index_create_group_link')) ?></p>


            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>