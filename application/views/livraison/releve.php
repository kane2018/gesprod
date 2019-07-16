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
                    <div class="box-header box-primary">
                        <h3 class="box-title">Relevé d'une journée</h3>
                    </div>
                    <!-- /.box-header -->
                    <form action="<?php echo site_url(uri_string()) ?>" method="post">
                        <div class="box-body">

                            <div class="form-group">
                                <label>Date:</label>

                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="date" class="form-control pull-right" id="datepicker">
                                </div>
                                <!-- /.input group -->
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Afficher</button>
                        </div>

                    </form>

                </div>

                

                <div class="box">
                    <div class="box-header box-primary">
                        <h3 class="box-title">Livraisons des clients avec transport</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Date de livraison</th>
                                <th>Clients</th>
                                <th>Téléphone</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Destination</th>
                                <th>Véhicule</th>
                                <th>Nom Frais</th>
                                <th>Somme Frais</th>
                            </tr>

                            <?php
                            $i = 1;
                            foreach ($releveclients as $cc):
                                ?>
                                <tr>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->prenom . ' ' . $cc->nom; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->telephone; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->designation; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->quantite; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->total; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->adresse; ?></td>
                                    <td rowspan="<?php echo $cc->frais != null ? count($cc->frais) + 1 : ''; ?>"><?php echo $cc->immatricule; ?></td>

                                </tr>
                                <?php if ($cc->frais != null): ?>
                                    <?php foreach ($cc->frais as $f): ?>
                                        <tr>
                                            <td><?php echo $f->nomFrais; ?></td>
                                            <td><?php echo $f->somme; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php
                                $i++;
                            endforeach;
                            ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">


                    </div>
                </div>

                <div class="box">
                    <div class="box-header box-primary">
                        <h3 class="box-title">Livraisons directes pour les clients</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Date de livraison</th>
                                <th>Clients</th>
                                <th>Téléphone</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Destination</th>
                            </tr>

                            <?php
                            $i = 1;
                            foreach ($releveclientsdirects as $cc):
                                ?>
                                <tr>
                                    <td><?php echo dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) ?></td>
                                    <td><?php echo $cc->prenom . ' ' . $cc->nom; ?></td>
                                    <td><?php echo $cc->telephone; ?></td>
                                    <td><?php echo $cc->designation; ?></td>
                                    <td><?php echo $cc->quantite; ?></td>
                                    <td><?php echo $cc->total; ?></td>
                                    <td><?php echo $cc->adresse; ?></td>

                                </tr>


                                <?php
                                $i++;
                            endforeach;
                            ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">


                    </div>
                </div>

                <div class="box">
                    <div class="box-header box-primary">
                        <h3 class="box-title">Impression relevé</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">


                        <a target="_blank" href="<?php echo site_url('livraison/imprimerReleve'); ?>" class="btn btn-primary pull-right">Imprimer <span class="glyphicon glyphicon-print"></span></a>


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
