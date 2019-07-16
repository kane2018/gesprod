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
                        <h3 class="box-title">Commandes ou Achats des clients</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Numéro de la commande</th>
                                <th>Date de la commande</th>
                                <th>Prénom et Nom du client</th>
                                <th>Adresse du client</th>
                                <th>Téléphone du client</th>
                                <th>Détail de la commande</th>
                                <th>Statut de la commande</th>
                                <th>Livrer la commande</th>
                            </tr>

                            <?php $i = 1; foreach ($commandeClients as $cc): ?>
                                <tr>
                                    <td><?php echo $cc->idCommande; ?></td>
                                    <td><?php echo dateFormat('d-m-Y H:i:s', $cc->datecreation) ?></td>
                                    <td><?php echo $cc->prenom.'<br/>'.$cc->nom; ?></td>
                                    <td><?php echo $cc->adresse; ?></td>
                                    <td><?php echo $cc->telephone; ?></td>
                                    
                                    <td>

                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info<?php echo $i; ?>">
                                            <span class="fa fa-eye"></span>
                                        </button>

                                        <div class="modal modal-info fade" id="modal-info<?php echo $i; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Détails de la Commande</h4>
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
                                                                        <th>Numéro</th>
                                                                        <th>Nom du produit</th>
                                                                        <th>Quantité</th>
                                                                        <th>Prix unitaire</th>
                                                                        <th>Total</th>
                                                                    </tr>

                                                                    <?php foreach ($cc->produits as $pc): ?>
                                                                        <tr>
                                                                            <td><?php echo $pc->idProd; ?></td>
                                                                            <td><?php echo $pc->nomProd; ?></td>
                                                                            <td><?php echo $pc->quantite; ?></td>
                                                                            <td><?php echo $pc->prix; ?></td>
                                                                            <td><?php echo $pc->total; ?></td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </table>
                                                            </div>
                                                            <!-- /.box-body -->

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                        <!--                                <button type="button" class="btn btn-outline">Save changes</button>-->
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </td>
                                    <td>
                                        <?php if($cc->statut == 1): ?> <button class="btn btn-success"><span class="fa fa-thumbs-o-up"></span></button><?php endif; ?>
                                        <?php if($cc->statut == 0): ?> <button class="btn btn-warning"><span class="fa  fa-thumbs-o-down"></span></button><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($cc->statut == 0): ?>
                                        <a class="btn btn-success" href="<?php echo site_url('commande/livrerClient/'.$cc->idCommande); ?>"><span class="fa fa-paper-plane-o"></span></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php $i++; endforeach; ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">


                    </div>
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





