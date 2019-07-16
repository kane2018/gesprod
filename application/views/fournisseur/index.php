<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">
   
    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1>Fournisseurs</h1>
                <p>Ci-dessous se trouve la liste des fournisseurs.</p>

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
                                <th>Société</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($fournisseurs as $fournisseur): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fournisseur->prenom, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($fournisseur->nom, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($fournisseur->telephone, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($fournisseur->company, ENT_QUOTES, 'UTF-8'); ?></td>
                                    
                                    <td><?php echo anchor("fournisseur/edit/" . $fournisseur->idFour, 'Edit'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('fournisseur/create', 'Créer un nouveau fournisseur') ?></p>


            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>

