<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sélectionner le type de livraison</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open(uri_string()); ?>
                    <div class="box-body">

                        <p>
                            <label for="avec">Livraison avec nos véhicules </label>
                            <input type="radio" name="choix" value="avec" id="avec" />
                            <label for="sans">Livraison directe</label>
                            <input type="radio" name="choix" value="sans" id="sans" checked="checked" />
                        </p>
                        
                        <div id="avecChauffeur">
                        
                        <div class="form-group">
                            <label for="chauffeur">Sélectionner le chauffeur</label>

                            <?php echo form_dropdown('chauffeur', $chauffeurs, '', 'class="form-control" id="chauffeur"'); ?>

                        </div>

                        <div class="form-group">
                            <label for="idvehicule">Immatriculation du véhicule</label>
                            <select name="vehicule" id="idvehicule" class="form-control">
                                <!--                                                <option></option>-->
                            </select>

                        </div>
                        </div>

                        <?php echo form_hidden('commande', $commande); ?>

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

        $("#chauffeur").change(function (e) {
            var id = encodeURIComponent($("#chauffeur").val());
            $('#idvehicule').empty();
            if (id !== "") {
                $.ajax({
                    url: '<?php echo site_url('commande/getVehicule'); ?>',
                    method: 'post',
                    data: {idChauf: id},
                    dataType: 'json',
                    success: function (json) {
                        $('#idvehicule').append(json);
                    }
                });
            }
        });
        
        
        $("#avecChauffeur").hide(1000);

        $("input#avec").on('ifChanged', function () {
            $("#avecChauffeur").slideDown(1000);
            //$("#infoclient").hide(1000);
        });

        $("input#sans").on('ifChanged', function () {
            $("#avecChauffeur").hide(1000);
            //$("#infoclient").slideDown(1000);
        });

    

    });
</script>

