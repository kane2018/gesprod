<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1><?php echo 'Catégories'; ?></h1>
                <p><?php echo 'Ci-dessous se trouve la liste des catégories'; ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Liste des catégories</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <table class="table table-bordered">
                            <tr>
                                <th>Nom de la catégorie</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($categories as $categorie): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($categorie->nomCat, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($categorie->description, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo anchor("categorie/edit/" . $categorie->idCat, ' Editer', array('class' => 'fa fa-edit')); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>

                <p><?php echo anchor('categorie/create', 'Créer une catégorie') ?> </p>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>
