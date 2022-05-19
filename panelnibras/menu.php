<div class="collapse navbar-collapse" id="menuku">
    <!-- menu samping -->

    <ul class="nav navbar-nav menu-samping in" id="menu-samping">
        <li id="menu-dashboard"><a
                href="<?php echo URL_PROGRAM_ADMIN ?>home?u_token=<?php echo $_SESSION["u_token"] ?>"><span
                    class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnmaster" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Master Data <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnmaster">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "negara/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Negara</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "propinsi/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Propinsi</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kabupaten/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Kotamadya/Kabupaten</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kecamatan/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Kecamatan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "status-order/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Status Order</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "bank/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Bank</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "rekening-bank/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Rekening Bank</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnshipping" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-compressed"></span> Shipping <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnshipping">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "shipping/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Kurir</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "biaya-packing/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span>Biaya Packing</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnpelanggan" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-tower"></span> Pelanggan <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnpelanggan">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-grup/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Grup Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-poin/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Poin Pelanggan</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-saldo/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Saldo Pelanggan</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnkatalog" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-credit-card"></span> Katalog <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnkatalog">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "warna/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Warna</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "ukuran/?u_tokssen=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Ukuran</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "kategori/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Kategori</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "produk-head/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Head Produk</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "produk/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Produk</a></li>
            </ul>
        </li>
        <li><a href='<?php echo URL_PROGRAM_ADMIN . "order/?u_token=" . $_SESSION["u_token"] ?>' id="mnorder"><span
                    class="glyphicon glyphicon-shopping-cart"></span> Order </a></li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnorder" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-send"></span> Pengiriman <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnorder">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "pengiriman?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Pengiriman</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "resi?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Input Resi</a></li>
                <!-- <li><a href="<?// echo URL_PROGRAM_ADMIN . "lap-order?op=view_daily&u_token=" . $_SESSION["u_token"] ?>"><span class="glyphicon glyphicon-record"></span> Order Daily</a></li> -->
            </ul>
        </li>
        <li><a href='<?php echo URL_PROGRAM_ADMIN . "lap-in-booking/?u_token=" . $_SESSION["u_token"] ?>'
                id="mnorder"><span class="glyphicon glyphicon-record"></span> in-booking </a></li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnorder" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-tasks"></span> Laporan <b class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnorder">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-order?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Order</a></li>
                <li><a
                        href="<?php echo URL_PROGRAM_ADMIN . "lap-order?op=view_daily&u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Order Daily</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-customer?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Pelanggan</a></li>
                <li><a
                        href="<?php echo URL_PROGRAM_ADMIN . "lap-customer?op=view_daily&u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Pelanggan Daily</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-produk?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Produk</a></li>
                <li><a
                        href="<?php echo URL_PROGRAM_ADMIN . "lap-produk?op=bykategori&u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Produk per Kategori</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "lap-produklaris?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> 10 Produk Terlaris</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href='javascript:void(0)' id="mnuser" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> User Administrator <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnorder">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "grup-user/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Grup User</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "user/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> User</a></li>
            </ul>
        </li>

        <li class="dropdown"><a href='javascript:void(0)' id="mnpengaturan" class="dropdown-toggle"
                data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Pengaturan <b
                    class="caret"></b></a>
            <ul class="dropdown-menu" id="dropmnpengaturan">
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "setting-toko/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Setting Toko</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "slideshow/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Slideshow</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "customer-support/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Customer Support</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "informasi/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Informasi</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . "testimonial/?u_token=" . $_SESSION["u_token"] ?>"><span
                            class="glyphicon glyphicon-record"></span> Testimonial</a></li>
            </ul>
        </li>
    </ul>

    <!-- /menu samping -->

    <ul class="nav menu-atas navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>
                <?php echo $_SESSION["userlogin"] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="#">Profil</a></li>
                <li><a href="<?php echo URL_PROGRAM_ADMIN . '?keluar' ?>">Keluar</a></li>
            </ul>
        </li>
        <li><a href="<?php echo URL_PROGRAM ?>" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> Lihat
                Situs</a></li>
    </ul>

</div><!-- /.navbar-collapse -->
</nav>