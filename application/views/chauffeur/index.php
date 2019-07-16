<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">
   
    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1>Chauffeurs</h1>
                <p>Ci-dessous se trouve la liste des chauffeurs.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bordered Table</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <table class="table table-bordered">
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Véhicule</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($chauffeurs as $chauffeur): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($chauffeur->prenomChauf, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($chauffeur->nomChauf, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($chauffeur->telephone, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($chauffeur->immatricule, ENT_QUOTES, 'UTF-8').'<br/>'.htmlspecialchars($chauffeur->marque, ENT_QUOTES, 'UTF-8'); ?></td>
                                    
                                    <td><?php echo anchor("chauffeur/edit/" . $chauffeur->idChauf, 'Edit'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('chauffeur/create', 'Créer un nouveau chauffeur') ?></p>


            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>

