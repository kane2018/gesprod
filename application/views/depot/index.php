<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1><?php echo 'Dépôts'; ?></h1>
                <p><?php echo 'Ci-dessous se trouve la liste des dépôts'; ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Liste des dépots</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <table class="table table-bordered">
                            <tr>
                                <th>Nom dépot</th>
                                <th>Lieu</th>
                                <th>Date de création</th>
                                <th>Gérants</th>
                                <th colspan="2">Action</th>
                            </tr>
                            <?php $i = 1; foreach ($depots as $depot): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($depot->nomDepot, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($depot->lieu, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo dateEnFrancais('%A %e %B %Y', $depot->datecreation); ?></td>
                                    
                                    <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-charge<?php echo $i; ?>">
                                                <span class="glyphicon glyphicon-zoom-in"></span>
                                            </button>

                                            <div class="modal modal-info fade" id="modal-charge<?php echo $i; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Liste des gérants du dépots</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box box-primary">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title"></h3>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <!-- form start -->

                                                                <div class="box-body table-responsive" style="color: #000077">
                                                                    <table class="table table-bordered table-condensed">
                                                                        <tr>
                                                                            <th>Prénom</th>
                                                                            <th>Nom</th>
                                                                            <th>Numéro de téléphone</th>
                                                                            <th>Date d'ajout</th>
                                                                            <th>Action</th>
                                                                        </tr>

        <?php foreach ($depot->users as $pc): ?>
                                                                            <tr>
                                                                                <td><?php echo $pc->first_name; ?></td>
                                                                                <td><?php echo $pc->last_name; ?></td>
                                                                                <td><?php echo $pc->phone; ?></td>
                                                                                <td><?php echo $pc->datecreation; ?></td>
                                                                                <td><a href="<?php echo site_url('depot/edituser/' . $pc->id.'/'.$depot->idDepot); ?>" class="btn btn-warning"><span class="fa fa-remove"></span></a></td>
                                                                                
                                                                            </tr>
        <?php endforeach; ?>
                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                            
                                                            <a href="<?php echo site_url('depot/adduser/'.$depot->idDepot); ?>" class="btn btn-primary"><span class="fa fa-plus-square"></span></a>
                                                            
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                    </td>
                                    
                                    <td><?php echo anchor("depot/edit/" . $depot->idDepot, ' Editer', array('class' => 'fa fa-edit')); ?></td>
                                    <?php if($depot->principal == 0): ?>
                                    <td><?php echo anchor("depot/definir/" . $depot->idDepot, ' Principal', array('class' => 'fa fa-save')); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php $i++; endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('depot/create', lang('depot_create')) ?> </p>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>
