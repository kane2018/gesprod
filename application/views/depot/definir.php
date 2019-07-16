<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">
    
    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-2 col-md-8">

                <h1><?php echo 'Définir comme dépôt principal'; ?></h1>
                <p><?php echo sprintf('Êtes-vous certain de vouloir définir le dépot : %s comme dépôt principal', $depot->nomDepot); ?></p>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Définition</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <?php echo form_open("depot/definir/" . $depot->idDepot); ?>

                        <p>
                            <?php echo lang('deactivate_confirm_y_label', 'confirm'); ?>
                            <input type="radio" name="confirm" value="yes" checked="checked" />
                            <?php echo lang('deactivate_confirm_n_label', 'confirm'); ?>
                            <input type="radio" name="confirm" value="no" />
                        </p>

                        <?php echo form_hidden($csrf); ?>
                        <?php echo form_hidden(['id' => $depot->idDepot]); ?>

                        <p><?php echo form_submit('submit', 'Enregistrer', array('class' => 'btn btn-primary')); ?></p>

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