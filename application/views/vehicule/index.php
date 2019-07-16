<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">
   
    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1>Véhicules</h1>
                <p>Ci-dessous se trouve la liste des véhicules.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bordered Table</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <table class="table table-bordered">
                            <tr>
                                <th>Immatricule</th>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($vehicules as $vehicule): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($vehicule->immatricule, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($vehicule->marque, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($vehicule->modele, ENT_QUOTES, 'UTF-8'); ?></td>
                                    
                                    
                                    <td><?php echo anchor("vehicule/edit/" . $vehicule->idVeh, 'Edit'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('vehicule/create', 'Enregistrer un nouveau véhicule') ?></p>


            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>

