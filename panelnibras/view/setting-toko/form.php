<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>

    <div class="widget-box">
        <div class="widget-title"><span class="icon"><i class="icon-file"></i></span>
            <h5>FORM <?php echo strtoupper($judul) ?></h5>
        </div>
        <div class="widget-content nopadding">
            <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <input type="hidden" id="aksi" name="aksi" value="simpan">
                <div id="hasil" style="display: none;"></div>
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tabdataweb" aria-controls="tabdataweb" role="tab" data-toggle="tab">Data Website</a></li>
                        <li role="presentation"><a href="#tabdatatoko" aria-controls="datatoko" role="tab" data-toggle="tab">Data Toko</a></li>
                        <li role="presentation"><a href="#tabdatabelanja" aria-controls="datatoko" role="tab" data-toggle="tab">Pengaturan Belanja</a></li>
                        <li role="presentation"><a href="#tabdatasetgambar" aria-controls="datasetgambar" role="tab" data-toggle="tab">Pengaturan Gambar</a></li>
                        <li role="presentation"><a href="#tabdatasosmed" aria-controls="datasosmed" role="tab" data-toggle="tab">Sosial Media</a></li>
                        <li role="presentation"><a href="#tabdataproduk" aria-controls="dataproduk" role="tab" data-toggle="tab">Pengaturan Produk</a></li>
                        <li role="presentation"><a href="#tabdataapi" aria-controls="dataapi" role="tab" data-toggle="tab">API Integrasi Shipping</a></li>
                        <li role="presentation"><a href="#tabapicekmutasi" aria-controls="dataapi" role="tab" data-toggle="tab">API Integrasi Cek Mutasi</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabdataweb">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Judul Website</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="judulsite" name="config_jdlsite" placeholder="Judul Site" class="form-control" value="<?php echo $data['config_jdlsite'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Slogan Website</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="slogansite" name="config_slogansite" placeholder="Slogan" class="form-control" value="<?php echo $data['config_slogansite'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Alamat Website</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="alamatsite" name="config_alamatsite" placeholder="Alamat Site" class="form-control" value="<?php echo $data['config_alamatsite'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-5">
                                        <textarea rows="3" class="form-control" id="deskripsi" name="config_deskripsitag"><?php echo $data['config_deskripsitag'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Keyword</label>
                                    <div class="col-sm-5">
                                        <textarea rows="3" class="form-control" id="keyword" name="config_keywordtag"><?php echo $data['config_keywordtag'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Google Analytics</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="googleanalisis" name="config_googleanalisis" placeholder="Google Analytics" class="form-control" value="<?php echo $data['config_googleanalisis'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Term Check Out
                                        <br>
                                        <cite>Halaman Term Check Out atau Selesai belanja</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="checkout" name="config_termcheckout" class="form-control">
                                            <?php foreach ($informasi as $info) { ?>
                                                <option value="<?php echo $info['id'] ?>" <?php if ($data['config_termcheckout'] == $info['id']) echo "selected" ?>><?php echo stripslashes($info['nm']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Term Register<br>
                                        <cite>Halaman Term Register / Pendaftaran</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="account" name="config_termaccount" class="form-control">
                                            <?php foreach ($informasi as $info) { ?>
                                                <option value="<?php echo $info['id'] ?>" <?php if ($data['config_termaccount'] == $info['id']) echo "selected" ?>><?php echo stripslashes($info['nm']) ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Cara Belanja<br>
                                        <cite>Halaman Cara Belanja</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="belanja" name="config_termbelanja" class="form-control">
                                            <?php foreach ($informasi as $info) { ?>
                                                <option value="<?php echo $info['id'] ?>" <?php if ($data['config_termbelanja'] == $info['id']) echo "selected" ?>><?php echo stripslashes($info['nm']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Order Status <br>
                                        <cite>Status Order saat pelanggan belum membayar atau saat membeli</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="orderstatus" name="config_orderstatus" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_orderstatus'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Order Cancel <br>
                                        <cite>Status Cancel Order : berlaku jika tidak membayar selama waktu yang telah ditentukan</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="orderstatus" name="config_ordercancel" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_ordercancel'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Konfirmasi Pembayaran<br>
                                        <cite>Status saat pelanggan mengkonfirmasi pembayaran</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="konfirmstatus" name="config_konfirmstatus" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_konfirmstatus'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Form Pengiriman/Shipping<br>
                                        <cite>Menampilkan Form Shipping/Kurir Saat Status Shipping</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="shippingstatus" name="config_shippingstatus" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_shippingstatus'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Status Sudah Bayar<br>
                                        <cite>Status Order ketika sudah bayar</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="sudahbayarstatus" name="config_sudahbayarstatus" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_sudahbayarstatus'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Order Selesai <br>
                                        <cite>Status Order dianggap selesai dan mendapatkan pendapatan</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <select id="orderselesai" name="config_orderselesai" class="form-control">
                                            <?php foreach ($datastatus as $ord) { ?>
                                                <option value="<?php echo $ord['ids'] ?>" <?php if ($data['config_orderselesai'] == $ord['ids']) echo "selected" ?>><?php echo stripslashes($ord['nms']) ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Dapat Reward Poin <br>
                                        <cite>Pelanggan akan dapat reward poin saat order berstatus : </cite>
                                    </label>

                                    <div class="col-sm-4">
                                        <?php foreach ($datastatus as $dts) { ?>
                                            <?php $checked = ''; ?>

                                            <?php if (in_array($dts['ids'], $data['config_getpoincust'])) $checked = "checked" ?>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="config_getpoincust[]" id="getpoincust" value="<?php echo $dts['ids'] ?>" <?php echo $checked ?>> <?php echo $dts['nms'] ?>
                                                </label>
                                            </div>

                                        <?php
                                        } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Edit Order <br>
                                        <cite>Order dapat diubah atau ditambah produk order saat order berstatus : </cite>
                                    </label>

                                    <div class="col-sm-4">
                                        <?php foreach ($datastatus as $dto) { ?>
                                            <?php $checked = ''; ?>

                                            <?php if (in_array($dto['ids'], $data['config_editorder'])) $checked = "checked" ?>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="config_editorder[]" id="editorder" value="<?php echo $dto['ids'] ?>" <?php echo $checked ?>> <?php echo $dto['nms'] ?>
                                                </label>
                                            </div>

                                        <?php
                                        } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Harga Tampil di Katalog</label>
                                    <div class="col-sm-4">
                                        <select id="memberdefault" name="config_memberdefault" class="form-control">
                                            <?php foreach ($datagrup as $grp) { ?>
                                                <option value="<?php echo $grp['id'] ?>" <?php if ($data['config_memberdefault'] == $grp['id']) echo "selected" ?>><?php echo stripslashes($grp['nm']) ?></option>
                                            <?php
                                            } ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Grup Pelanggan di Form Register</label>
                                    <div class="col-sm-4">
                                        <?php foreach ($datagrup as $grup) { ?>
                                            <?php $checked = ''; ?>

                                            <?php if (in_array($grup['id'], $data['config_grupcust'])) $checked = "checked" ?>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="config_grupcust[]" id="grupcust" value="<?php echo $grup['id'] ?>" <?php echo $checked ?>> <?php echo $grup['nm'] ?>
                                                </label>
                                            </div>

                                        <?php
                                        } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">SlideShow</label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="config_slideshow" id="slideshow" value="1" <?php if ($data['config_slideshow'] == '1') echo "checked" ?>> Tampil
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Pesan Maintenance</label>
                                    <div class="col-sm-9">
                                        <textarea id="note_maintenance" name="config_note_maintenance" class="form-control"><?php echo $data['config_note_maintenance'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Maintenance </label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="config_maintenance" id="maintenance" value="1" <?php if ($data['config_maintenance'] == '1') echo "checked" ?>> Ya/Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tabdatatoko">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Nama Toko</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="namatoko" name="config_namatoko" placeholder="Nama Toko" class="form-control" value="<?php echo $data['config_namatoko'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Nama Pemilik</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="namapemilik" name="config_namapemilik" placeholder="Nama Pemilik" class="form-control" value="<?php echo $data['config_namapemilik'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Alamat</label>
                                    <div class="col-sm-5">
                                        <textarea rows="3" class="form-control" id="alamattoko" name="config_alamattoko"><?php echo $data['config_alamattoko'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="email" name="config_email" placeholder="Email" class="form-control" value="<?php echo $data['config_email'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Telepon</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="telp" name="config_telp" placeholder="Telepon" class="form-control" value="<?php echo $data['config_telp'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Waktu Buka</label>
                                    <div class="col-sm-5">
                                        <textarea rows="3" class="form-control" id="openingtime" name="config_openingtime"><?php echo $data['config_openingtime'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tabdatabelanja">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Masa Pembayaran Belanja</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="masabayar" name="config_masabayar" placeholder="Masa Pembayara Belanja" class="form-control" value="<?php echo $data['config_masabayar'] ?>">
                                    </div>
                                    <label class="control-label">Hari</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Target Aktif Pelanggan</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="masabayar" name="config_targetaktif" placeholder="Target Aktif Pelanggan" class="form-control" value="<?php echo $data['config_targetaktif'] ?>">
                                    </div>
                                    <label class="control-label">Bulan</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email Notifikasi</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="emailnotif" name="config_emailnotif" placeholder="Email Notifikasi" class="form-control" value="<?php echo $data['config_emailnotif'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Header Format Email Nota</label>
                                    <div class="col-sm-9">
                                        <textarea id="headernotaemail" name="config_headernotaemail" class="form-control"><?php echo $data['config_headernotaemail'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Format Nota Registrasi</label>
                                    <div class="col-sm-9">
                                        <textarea id="notaregisweb" name="config_notaregisweb" class="form-control"><?php echo $data['config_notaregisweb'] ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Format Email Nota Belanja</label>
                                    <div class="col-sm-9">
                                        <textarea id="notabelanja" name="config_notabelanja" class="form-control"><?php echo $data['config_notabelanja'] ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Format Email Info Shipping</label>
                                    <div class="col-sm-9">
                                        <textarea id="infoshipping" name="config_infoshipping" class="form-control"><?php echo $data['config_infoshipping'] ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Format Email Info Sudah Bayar</label>
                                    <div class="col-sm-9">
                                        <textarea id="infosudahbayar" name="config_infosudahbayar" class="form-control"><?php echo $data['config_infosudahbayar'] ?></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tabdatasetgambar">
                            <div class="well">
                                <h4>Kategori</h4>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Thumbnail (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="kategorithumbnail_p" name="config_kategorithumbnail_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_kategorithumbnail_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="kategorithumbnail_l" name="config_kategorithumbnail_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_kategorithumbnail_l'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Small (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="kategorismall_p" name="config_kategorismall_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_kategorismall_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="kategorismall_l" name="config_kategorismall_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_kategorismall_l'] ?>">
                                    </div>
                                </div>
                                <hr>

                                <h4>Produk </h4>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Thumbnail (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkthumbnail_p" name="config_produkthumbnail_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_produkthumbnail_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkthumbnail_l" name="config_produkthumbnail_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_produkthumbnail_l'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Detail (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkdetail_p" name="config_produkdetail_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_produkdetail_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkdetail_l" name="config_produkdetail_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_produkdetail_l'] ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">zoom (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkzoom_p" name="config_produkzoom_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_produkzoom_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkzoom_l" name="config_produkzoom_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_produkzoom_l'] ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Small (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produksmall_p" name="config_produksmall_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_produksmall_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="produksmall_l" name="config_produksmall_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_produksmall_l'] ?>">
                                    </div>
                                </div>
                                <hr>
                                <h4>Bank </h4>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Logo (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="logobank_p" name="config_logobank_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_logobank_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="logobank_l" name="config_logobank_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_logobank_l'] ?>">
                                    </div>
                                </div>
                                <hr>
                                <h4>Shipping </h4>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Logo Kurir (Panjang x Lebar)</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="logokurir_p" name="config_logokurir_p" placeholder="Panjang" class="form-control" value="<?php echo $data['config_logokurir_p'] ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="logokurir_l" name="config_logokurir_l" placeholder="Lebar" class="form-control" value="<?php echo $data['config_logokurir_l'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tabdatasosmed">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Fanpage Facebook</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="pagefb" name="config_pagefb" placeholder="Fanpage Facebook" class="form-control" value="<?php echo $data['config_pagefb'] ?>">
                                    </div>
                                    <label class="control-label">ex: https://facebook.com/namafanpage</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Twitter</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="twitter" name="config_twitter" placeholder="Akun Twitter" class="form-control" value="<?php echo $data['config_twitter'] ?>">
                                    </div>
                                    <label class="control-label">ex: https://twitter.com/namaakun</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Instagram</label>
                                    <div class="col-sm-5">
                                        <input type="text" id="instagram" name="config_instagram" placeholder="Akun Instagram" class="form-control" value="<?php echo $data['config_instagram'] ?>">
                                    </div>
                                    <label class="control-label">ex: https://instagram.com/namaakun</label>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tabdataproduk">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Produk Home <br>
                                        <cite>Jumlah Produk di Halaman Depan</cite>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkhome" name="config_produkhome" placeholder="Jumlah Produk Home" class="form-control" value="<?php echo $data['config_produkhome'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">List Produk <br>
                                        <cite>Jumlah Produk per Halaman di List Produk </cite>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produklist" name="config_produklist" placeholder="Jumlah Produk di List Produk" class="form-control" value="<?php echo $data['config_produklist'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Produk Kategori<br>
                                        <cite>Jumlah Produk per Halaman di List Kategori</cite>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produkkategori" name="config_produkkategori" placeholder="Jumlah Produk di Kategori" class="form-control" value="<?php echo $data['config_produkkategori'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Produk Sale Home<br>
                                        <cite>Jumlah Produk Sale per halaman di Home </cite>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produksalehome" name="config_produksalehome" placeholder="Jumlah Produk Sale di Home" class="form-control" value="<?php echo $data['config_produksalehome'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">List Produk Sale <Br>
                                        <cite>Jumlah Produk Sale per Halaman di List Produk Sale</cite>
                                    </label>
                                    <div class="col-sm-2">
                                        <input type="text" id="produksalelist" name="config_produksalelist" placeholder="Jumlah Produk Sale di List" class="form-control" value="<?php echo $data['config_produksalelist'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tabdataapi">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Lokasi Origin <br>
                                        <cite>Lokasi Kota Asal Pengiriman</cite>
                                    </label>
                                    <div class="col-sm-3">
                                        <select id="lokasiorigin" name="config_lokasiorigin" class="form-control">
                                            <?php foreach ($datacity as $city) { ?>
                                                <option value="<?php echo $city['idk'] ?>" <?php if ($data['config_lokasiorigin'] == $city['idk']) echo "selected" ?>><?php echo stripslashes($city['nmk']) ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">API Key<br>
                                        <cite>API key dari Rajaongkir.com</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="apikeyongkir" name="config_apikeyongkir" placeholder="API Key" class="form-control" value="<?php echo $data['config_apikeyongkir'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">URL API<br>
                                        <cite>URL API dari Rajaongkir.com</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="apiurlongkir" name="config_apiurlongkir" placeholder="URL API Tarif" class="form-control" value="<?php echo $data['config_apiurlongkir'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="tabapicekmutasi">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">API Key<br>
                                        <cite>API Signature</cite>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="apikeyongkir" name="config_apisignature_cekmutasi" placeholder="API Key" class="form-control" value="<?php echo $data['config_apisignature_cekmutasi'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>

                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
</div>

<script src="<?php echo URL_PROGRAM_ADMIN_VIEW . folder . "/validasi.js" ?>"></script>