<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Détails de la commande</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Numéro</th>
                                <th>Nom</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Total</th>
                            </tr>

                            <?php foreach ($this->session->userdata('panierDepot') as $p): ?>
                                <tr>
                                    <td><?php echo $p['id']; ?></td>
                                    <td><?php echo $p['nom']; ?></td>
                                    <td><?php echo $p['quantite']; ?></td>
                                    <td><?php echo $p['prix']; ?></td>
                                    <td><?php echo $p['total']; ?></td>

                                </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">

                    </div>

                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dépot</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open("commande/finaliserDepot"); ?>
                    <div class="box-body">

                        <div class="form-group">
                            <label for="depot">Sélectionner le dépôt</label>

                            <?php echo form_dropdown('depot', $depots, '', 'class="form-control" id="depot"'); ?>

                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                    </form>
                </div>

                <!-- /.box -->



            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->    

<?php $this->load->view('include/pied') ?>
