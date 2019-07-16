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

                            <?php foreach ($this->session->userdata('panier') as $p): ?>
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
                        <h3 class="box-title">Information du client</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open("commande/finaliserclient"); ?>
                    <div class="box-body">

                        <p>
                            <label for="ancien">Pour un ancien client</label>
                            <input type="radio" name="choix" value="ancien" id="ancien" />
                            <label for="nouveau">Pour un nouveau client</label>
                            <input type="radio" name="choix" value="nouveau" id="nouveau" checked="checked" />
                        </p>

                        <div class="form-group" id="choixclient">
                            <label for="client">Sélectionner le client</label>

                            <?php echo form_dropdown('client', $clients, '', 'class="form-control" id="client"'); ?>

                        </div>
                        <div id="infoclient">
                            <div class="form-group">
                                <label for="prenom">Prénom du client</label>
                                <?php echo form_input($prenom, null, array('class' => 'form-control')); ?>
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom du client</label>
                                <?php echo form_input($nom, null, array('class' => 'form-control')); ?>
                            </div>

                            <div class="form-group">
                                <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                                <?php echo form_input($phone, null, array('class' => 'form-control', 'data-inputmask' => '\'mask\': \'99 999 99 99\'', 'data-mask' => '')); ?>
                            </div>

                            <div class="form-group">
                                <label for="adresse">Adresse du client</label>
                                <?php echo form_input($adresse, null, array('class' => 'form-control')); ?>
                            </div>

                        </div>

                        <!-- select -->

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

<script>
    $(document).ready(function () {

        $("#choixclient").hide(1000);

        $("input#ancien").on('ifChanged', function () {
            $("#choixclient").slideDown(1000);
            $("#infoclient").hide(1000);
        });

        $("input#nouveau").on('ifChanged', function () {
            $("#choixclient").hide(1000);
            $("#infoclient").slideDown(1000);
        });

    });

</script>



