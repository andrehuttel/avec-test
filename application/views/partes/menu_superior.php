<div class="navbar navbar-fixed-top">
    <div class="">
        <!---Logo-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="<?php echo base_url($this->session->userdata('url_default')) ?>">
                <img src='<?php echo base_url(); ?>uploads/visual/<?= $this->session->userdata('logo') ?>' style="height: 51px; margin-top:-15px;">
            </a>
        </div>

        <!--Contacts-->
        <ul class="nav navbar-nav botao_top mobile-ajuste" role="menu">
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>contacts"><i class="glyphicon glyphicon-list" style='color:#fff;'></i>  <font style='color:#fff;'>Contacts</font> </a>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right" role="menu" aria-labelledby="dropdownMenu">
            <li class="dropdown mobile-ajuste-layout">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src='<?= base_url(); ?>assets/img/user.png'>
                    <b class="caret"></b></font>
                </a>

                <ul class="dropdown-menu">
                    <li class="dropdown-header"><?php echo $this->session->userdata('username'); ?></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo base_url()?>auth/logout" id="sair_sistema"><i class="glyphicon glyphicon-log-out"></i> Sair</a></li>
                </ul>
            </li>
        </ul>

    </div>
</div>

<style>.navbar .dropdown a:hover, .botao_top a:hover, .navbar .dropdown-menu a:hover, .nav .open > a, .nav .open > a:focus, .nav .open > a:hover{background: <?= $cor_topo_hover; ?> !important;}.navbar-fixed-top{background: blue;}</style>
