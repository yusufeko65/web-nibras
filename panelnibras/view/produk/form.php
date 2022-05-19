<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>

    <div class="widget-box">
        <div class="widget-title"><span class="icon"><i class="icon-file"></i></span>
            <h5>FORM <?php echo strtoupper($judul) ?></h5>
        </div>
        <div class="widget-content nopadding">
            <form autocomplete="off" class="form-horizontal" method="POST" name="frmdata" id="frmdata" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                <input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
                <input type="hidden" name="idproduk" id="idproduk" value="<?php echo isset($idproduk) ? $idproduk : 0 ?>">
                <input type="hidden" name="produklama" id="produklama" value="<?php echo $produk_nama ?>">
                <input type="hidden" name="kodelama" id="kodelama" value="<?php echo $produk_kode ?>">

                <div id="hasil" style="display: none;"></div>
                <div role="tabpanel">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tabketerangan" aria-controls="tabketerangan" role="tab" data-toggle="tab">Keterangan Produk</a></li>
                        <li role="presentation"><a href="#tabharga" aria-controls="tabharga" role="tab" data-toggle="tab">Harga</a></li>
                        <?php if ($modul == 'ubah') { ?>
                            <li role="presentation"><a href="#tabhargaspesial" aria-controls="tabhargaspesial" role="tab" data-toggle="tab">Diskon</a></li>
                            <li role="presentation"><a href="#tabgbr" aria-controls="tabgbr" role="tab" data-toggle="tab">Gambar + Warna</a></li>
                            <li role="presentation"><a href="#taboption" aria-controls="taboption" role="tab" data-toggle="tab">Stok per Warna/Ukuran</a></li>
                            <li role="presentation"><a href="#tabgbrdetail" aria-controls="tabgbrdetail" role="tab" data-toggle="tab">Gambar Detail</a></li>
                        <?php
                    } ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabketerangan">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Product Head</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="producthead_nama" name="producthead_nama" class="form-control forms" autocomplete="off" value="<?php echo $producthead_nama ?>" placeholder="Masukkan Induk Produk">
                                        <input type="hidden" id="producthead_id" name="producthead_id" value="<?php echo $producthead_id ?>" class="form-control forms">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kode Produk</label>
                                    <div class="col-sm-2">
                                        <input type="text" placeholder="Kode Produk" id="kode_produk" name="kode_produk" class="form-control forms" value="<?php echo $produk_kode ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Produk</label>
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="Nama Produk" id="nama_produk" name="nama_produk" class="form-control forms" value="<?php echo stripslashes($produk_nama) ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kategori</label>
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="Masukkan Nama Kategori" id="kategori_nama" name="kategori_nama" class="form-control forms" autocomplete="off" value="<?php echo $namakat['name'] ?>">
                                        <div id="product-category" class="well well-sm" style="height:80px; overflow: auto;cursor:pointer">
                                            <?php if ($datakat) { ?>
                                                <?php foreach ($datakat as $kategori) { ?>
                                                    <div id="product-category<?php echo $kategori['idkategori'] ?>">
                                                        <div class="item-kat"><i class="icon-minus-sign"></i> <?php echo $kategori['nama_kategori'] ?></div>
                                                        <input type="hidden" id="idkategori<?php echo $kategori['idkategori'] ?>" name="idkategori[]" value="<?php echo $kategori['idkategori'] ?>" class="form-control forms">
                                                    </div>
                                                <?php
                                            }
                                        } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Cover Depan</label>
                                    <div class="col-sm-6">
                                        <input name="gbr_produk" id="gbr_produk" type="file">
                                        <input type="hidden" value="<?php echo $produk_gbr ?>" id="gbr_produk_lama" name="gbr_produk_lama">
                                        <?php if ($produk_gbr != '') { ?>
                                            <br>
                                            <img src="<?php echo URL_PROGRAM . 'assets/image/_small/small_gproduk' . $produk_gbr ?>">
                                        <?php
                                    } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Berat</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="berat_produk" name="berat_produk" value="<?php echo $berat ?>">
                                    </div>
                                    <label class="control-label">Gram</label>
                                </div>
                                <?php if ($modul == "ubah") { ?>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Sisa Stok</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="jml_stok" name="jml_stok" value="<?php echo $stok ?>">
                                        </div>
                                    </div>
                                <?php
                            } ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-10">
                                        <textarea cols="80" class="form-control" id="keterangan_produk" name="keterangan_produk"><?php echo trim(stripslashes(html_entity_decode($keterangan))) ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Meta Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea cols="80" class="form-control" id="metatag_deskripsi" name="metatag_deskripsi"><?php echo trim(stripslashes(html_entity_decode($metadeskripsi))) ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Keyword</label>
                                    <div class="col-sm-10">
                                        <textarea cols="80" class="form-control" id="metatag_keyword" name="metatag_keyword"><?php echo trim(stripslashes(html_entity_decode($metakeyword))) ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Alias URL</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="alias_url" name="alias_url" value="<?php echo $aliasurl ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-2">
                                        <select id="status_produk" name="status_produk" class="form-control">
                                            <option value="1" <?php if ($status == 1) echo "selected" ?>>Enabled</option>
                                            <option value="0" <?php if ($status == 0) echo "selected" ?>>Disabled</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tabharga">
                            <div class="well">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Reward Poin</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="poin" name="poin" value="<?php echo $reward_poin ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Harga Satuan</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="hrg_jual" name="hrg_jual" value="<?php echo $harga_satuan ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="alert alert-info">Catatan : <br>
                                        <ul>
                                            <li>Masukkan Harga tanpa koma ataupun titik</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php if ($modul == 'ubah') { ?>
                            <div role="tabpanel" class="tab-pane" id="tabhargaspesial">
                                <div class="well">
                                    <div id="alertdiskon"></div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Diskon Satuan</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="hrg_diskon" name="hrg_diskon" value="<?php echo $harga_diskon_satuan ?>">
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="text" class="form-control" id="persen_diskon" name="persen_diskon" value="<?php echo $persen_diskon_satuan ?>">
                                        </div>
                                        <div class="col-sm-1"> (%) </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="alert alert-info">Catatan : <br>
                                            <ul>
                                                <li>Masukkan Harga tanpa koma ataupun titik</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>



                            <!-- warna dan gambar -->

                            <div role="tabpanel" class="tab-pane" id="tabgbr">
                                <input type="hidden" name="actiondata" id="actiondata" value="uploadwarna">


                                <div class="well">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Warna</label>
                                        <div class="col-sm-5">
                                            <select class="form-control input-sm" data-placeholder="Pilih Warna..." tabindex="1" name="idwarna" id="idwarna" style="width:200px">
                                                <?php echo $combowarna; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">File</label>
                                        <div class="col-sm-5">
                                            <input type="file" name="produk_image" id="produk_image" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"></label>
                                        <div class="col-sm-5">
                                            <button id="btngambarwarna" type="button" class="btn btn-sm btn-info" onclick="uploadWarna();">Upload Gambar</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="alert alert-info">Catatan : <br>
                                            <ul>
                                                <li>Jika warna sudah tersedia / sudah memiliki gambar, maka otomatis akan terupdate gambar sesuai warna</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-right">
                                            Jumlah Warna : <span id="jmlwarna"><?php echo count($datawarna) ?></span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped table-hover tabel-grid" id="tbgambarwarna">
                                        <thead>
                                            <tr>
                                                <th width="1%" class="text-center">No</th>
                                                <th>Warna</th>
                                                <th>Gambar</th>
                                                <th width="1%">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodywarna">
                                            <?php if ($datawarna) { ?>
                                                <?php $no = 1; ?>
                                                <?php foreach ($datawarna as $warna) { ?>
                                                    <tr id="image_warna_row<?php echo $image_warna_row ?>">
                                                        <td class="text-center"><?php echo $no ?></td>
                                                        <td><?php echo $warna['warna'] ?></td>
                                                        <td><img src="<?php echo URL_PROGRAM . 'assets/image/_small/small_gproduk' . $warna['gbr'] ?>"></td>
                                                        <td><button type="button" id="btnhapuswarna<?php echo $image_warna_row ?>" onclick="hapusWarna('<?php echo $image_warna_row ?>','<?php echo $idproduk ?>','<?php echo $warna['idwarna'] ?>')" class="btn btn-danger btn-sm">Hapus</button></td>
                                                    </tr>
                                                    <?php $no++ ?>
                                                    <?php $image_warna_row++ ?>
                                                <?php
                                            } ?>
                                            <?php
                                        } ?>
                                            <tr id="tbfootwarna"></tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <!-- end warna dan gambar -->

                            <!-- Stock per Warna/Ukuran -->
                            <div role="tabpanel" class="tab-pane" id="taboption">
                                <div class="well">
									
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Kode Produk</label>
                                        <div class="col-sm-5">
										
                                            <input type="text" disabled class="form-control-static form-control input-sm" value="<?php echo $produk_kode ?>">
                                        
                                            
                                        </div>
                                    </div>
									
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Ukuran</label>
                                        <div class="col-sm-5">
                                            <select class="form-control input-sm" data-placeholder="Pilih Ukuran..." tabindex="1" name="id_ukuran" id="id_ukuran" style="width:200px">
                                                <?php echo $dtFungsi->cetakcombobox3('- Ukuran -', 0, 0, 'id_ukuran', '_ukuran ORDER BY ukuran ASC', 'idukuran', 'ukuran') ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Warna</label>
                                        <div class="col-sm-5">
                                            <select class="form-control input-sm" data-placeholder="Pilih Warna..." tabindex="1" name="id_warna" id="id_warna" style="width:200px">
                                                <?php echo $combowarna; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Stok</label>
                                        <div class="col-sm-1">
                                            <input type="text" name="stok_option" id="stok_option" class="form-control input-sm" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Tambahan Harga</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="tambahan_harga" id="tambahan_harga" class="form-control input-sm" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"></label>
                                        <div class="col-sm-5">
                                            <button id="btnstokoption" type="button" class="btn btn-sm btn-info" onclick="simpanstokoption();">Simpan Stok</button>
                                        </div>
                                    </div>


                                    <table class="table table-bordered table-striped table-hover tabel-grid" id="option-value<?php echo $option_row ?>">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Ukuran</th>
                                                <th class="text-center">Warna</th>
                                                <th class="text-center">Stok</th>
                                                <th class="text-center">Tambahan Harga</th>
                                                <th class="text-center">Harga</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyoption">
                                            <?php if ($dataallstokoption) { ?>
                                                <?php $option_row = 0 ?>
                                                <?php foreach ($dataallstokoption as $stokoption) { ?>
                                                    <tr id="option_row<?php echo $option_row ?>">
                                                        <td><?php echo $stokoption['ukuran'] ?></td>
                                                        <td><?php echo $stokoption['warna'] ?></td>
                                                        <td class="text-center"><a href="javascript:void(0)" onclick="formmodaleditstok(<?php echo $option_row ?>,<?php echo $idproduk ?>,<?php echo $stokoption['idukuran'] ?>,<?php echo $stokoption['idwarna'] ?>,'<?php echo $stokoption['ukuran'] ?>','<?php echo $stokoption['warna'] ?>')" id="stok<?php echo $option_row ?>"><?php echo $stokoption['stok'] ?></a></td>
                                                        <td class="text-right"><?php echo $stokoption['tambahan_harga'] ?></td>
                                                        <td class="text-right"><?php echo $stokoption['tambahan_harga'] + $harga_satuan ?></td>
                                                        <td class="text-center"><button type="button" id="btnhapusstok<?php echo $option_row ?>" onclick="hapusStokOption(<?php echo $option_row ?>,<?php echo $idproduk ?>,<?php echo $stokoption['idukuran'] ?>,<?php echo $stokoption['idwarna'] ?>,<?php echo $stokoption['stok'] ?>)" class="btn btn-danger btn-sm">Hapus</button></td>
                                                    </tr>
                                                    <?php $option_row++ ?>
                                                <?php
                                            } ?>
                                            <?php
                                        } ?>
                                            <tr id="tfooteroption"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end Stock per Warna/Ukuran -->

                            <!-- Gambar Detail -->
                            <div role="tabpanel" class="tab-pane" id="tabgbrdetail">
                                <div class="well">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">File</label>
                                        <div class="col-sm-5">
                                            <input type="file" name="produk_image_detail" id="produk_image_detail" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"></label>
                                        <div class="col-sm-5">
                                            <button id="btngambardetail" type="button" class="btn btn-sm btn-info" onclick="uploadGambarDetail();">Upload Gambar</button>
                                        </div>
                                    </div>


                                    <table class="table table-bordered table-striped table-hover tabel-grid" id="gbr-detail-value<?php echo $gbr_detail_row ?>">
                                        <thead>
                                            <tr>
                                                <th>Gambar</th>
                                                <th>Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodygbrdetail">
                                            <?php if ($datagambardetail) { ?>
                                                <?php foreach ($datagambardetail as $gbrdetail) { ?>
                                                    <tr id="gbr_detail_row<?php echo $gbr_detail_row ?>">
                                                        <td class="text-center"><img src="<?php echo URL_PROGRAM . 'assets/image/_small/small_gproduk' . $gbrdetail['gbr_detail'] ?>"></td>
                                                        <td class="text-center"><button type="button" id="btnhapusgambardetail<?php echo $gbr_detail_row ?>" onclick="hapusGambarDetail('<?php echo $gbr_detail_row ?>','<?php echo $idproduk ?>','<?php echo $gbrdetail['gbr_detail'] ?>')" class="btn btn-danger btn-sm">Hapus</button></td>
                                                    </tr>
                                                    <?php $gbr_detail_row++ ?>
                                                <?php
                                            } ?>
                                            <?php
                                        } ?>
                                            <tr id="tfootergbrdetail"></tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <!-- end Gambar Detail -->
                        <?php
                    } ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <?php if ($modul == 'ubah') { ?>
                            <a onclick="location='<?php echo URL_PROGRAM_ADMIN . folder . '?op=add' ?>'" class="btn btn-sm btn-success">Tambah Baru</a>
                        <?php
                    } ?>
                        <button type="submit" class="btn btn-sm btn-primary" id="btnsimpan">Simpan</button>
                        <a onclick="location='<?php echo URL_PROGRAM_ADMIN . folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    var action = $('#frmdata').prop('action');
    var image_warna_row = <?php echo $image_warna_row ?>;
    var gbr_detail_row = <?php echo $gbr_detail_row ?>;
    var option_row = <?php echo $option_row ?>;
    var tambahan_hrg_row = <?php echo $tambahan_hrg_row ?>;
    $(function() {

        $('#kode').focus();
        $('#keterangan_produk').summernote({
            height: "300px"
        });
        $("#frmdata").submit(function(event) {
            event.preventDefault();
            simpandata();
        });
        /* autocomplete product head */
        $('#producthead_nama').autocomplete({
            delay: 0,
            source: function(request, response) {
                $.ajax({
                    url: action,
                    dataType: "json",
                    data: {
                        loads: 'produkhead',
                        cari: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nama_produk,
                                value: item.nama_produk,
                                kode: item.head_idproduk,

                            }
                        }));
                    },
                    error: function(e) {
                        alert(action + '?loads=produkhead&cari=' + request.term);
                        alert('Error: ' + e);
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                $('#producthead_nama').val(ui.item.value);
                $('#producthead_id').val(ui.item.kode);

                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });
        $('#producthead_nama').change(function() {
            if ($('#producthead_nama').val() == '') {
                $('#producthead_id').val('0');
            }
            return false;
        });
        /* @end autocomplete product head */

        /* autocomplete */
        $('#kategori_nama').autocomplete({
            delay: 0,
            source: function(request, response) {
                $.ajax({
                    url: action,
                    dataType: "json",
                    data: {
                        loads: 'kategori',
                        cari: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name,
                                value: item.name,
                                kode: item.category_id,

                            }
                        }));
                    },
                    error: function(e) {
                        alert('Error: ' + e);
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                /*
                $('#kategori_nama').val(ui.item.value);
                $('#idkategori').val(ui.item.kode);
                */
                $('input[name=\'idkategori\']').val('');
                $('#product-category' + ui.item.kode).remove();
                $('#product-category').append('<div id="product-category' + ui.item.kode + '"><div class="item-kat"><i class="icon-minus-sign"></i> ' + ui.item.label + '<input type="hidden" id="idkategori' + ui.item.kode + '" name="idkategori[]" value="' + ui.item.kode + '" /></div></div>');
                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });

        /* @end autocomplete */

        $('#product-category').delegate('.item-kat', 'click', function() {
            $(this).parent().remove();
        });
        /* gambar warna */
        $("#idwarna").chosen({
            no_results_text: "Tidak Ada Warna!",
            width: "100%"
        });
        /* end gambar warna */

        /* ukuran stok option*/
        $("#id_ukuran").chosen({
            no_results_text: "Tidak Ada Ukuran!",
            width: "100%"
        });
        /* @end ukuran stok option */
        /* warna stok option*/
        $("#id_warna").chosen({
            no_results_text: "Tidak Ada Warna!",
            width: "100%"
        });
        /* @end warna stok option */
        /* mencari harga diskon menyesuaikan antara persen dan nominal */

        $('#hrg_diskon').change(function() {
            harga_diskon('harga_diskon')
        });
        $('#persen_diskon').change(function() {
            harga_diskon('persen')
        });
        /* end mencari harga diskon menyesuaikan antara persen dan nominal */
    });

    function harga_diskon(j) {

        $('#alertdiskon').removeClass();
        $('#alertdiskon').hide();
        var hrgsatuan = $('#hrg_jual').val();
        if (j == 'harga_diskon') {

            var selisih = hrgsatuan - $('#hrg_diskon').val();
            var persen = (selisih / hrgsatuan) * 100;
            if (persen % 1 != 0) {
                $('#alertdiskon').html('Masukkan Nilai Diskon yang sesuai, menghasilkan persen diskon berupa desimal tidak diterima');
                $('#alertdiskon').show();
                $('#alertdiskon').addClass("alert alert-danger");
                $('#persen_diskon').val('0');
            } else {
                $('#persen_diskon').val(persen);
            }
        } else {
            var persen = $('#persen_diskon').val();
            if (persen % 1 != 0) {
                $('#alertdiskon').html('Masukkan Nilai Diskon yang sesuai, menghasilkan persen diskon berupa desimal tidak diterima');
                $('#alertdiskon').show();
                $('#alertdiskon').addClass("alert alert-danger");
                $('#persen_diskon').val('0');
                $('#hrg_diskon').val('0');
            } else {
                var selisih = (hrgsatuan * persen) / 100;
                var hrgdiskon = hrgsatuan - selisih;
                if (hrgdiskon % 1 != 0) {
                    $('#alertdiskon').html('Masukkan Nilai Diskon yang sesuai, menghasilkan persen diskon berupa desimal tidak diterima');
                    $('#alertdiskon').show();
                    $('#alertdiskon').addClass("alert alert-danger");
                    $('#hrg_diskon').val('0');
                } else {
                    $('#hrg_diskon').val(hrgdiskon);
                }
            }

        }
        return false;
    }

    function simpandata() {
        var action = $('#frmdata').prop("action");
        var kategori = $('#kode_produk').val();
        var nama_produk = $('#nama_produk').val();
        var rv = true;
        $('#btnsimpan').button("loading");
        $('#hasil').removeClass();

        if (kategori.length < 1 && kategori.length > 10) {
            alert(kategori.length);
            $('#hasil').addClass('alert alert-danger');
            $('#hasil').html('Masukkan Kode Produk. Maksimal 10 Karakter');
            $('#hasil').show();
            $('#btnsimpan').button("reset");
            rv = false;
        }
        if (nama_produk == '' || nama_produk.length > 100) {
            $('#hasil').addClass('alert alert-danger');
            $('#hasil').html('Masukkan Nama Produk. Maksimal 100 Karakter');
            $('#hasil').show();
            $('#btnsimpan').button("reset");
            rv = false;
        }
        if ($('#producthead_nama').val() == '') {
            $('#producthead_id').val('0');
        }

        if (rv) {
            $.ajax({
                url: action,
                method: "POST",
                data: new FormData(frmdata),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(json) {

                    if (json['status'] == 'error') {

                        $('#hasil').addClass('alert alert-danger');
                        $('#btnsimpan').button("reset");

                    } else {

                        $('#hasil').addClass('alert alert-success');

                        if ($('#aksi').val() == 'tambah') {
                            location.href = '<?php echo URL_PROGRAM_ADMIN . folder . "/?op=edit&pid=" ?>' + json['produk_id'];
                        }
                    }
                    $('#hasil').show();
                    $('#hasil').html(json['result']);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                    $('#btnsimpan').button("reset");
                }
            });
        } else {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
        }
        return rv;
    }

    function uploadWarna() {
        $('#btngambarwarna').button("loading");
        $('#hasil').removeClass();
        $('#hasil').hide();
        var idproduk = $('#idproduk').val();
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: new FormData(frmdata),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');


                } else {
                    var html = '';
                    var jmlwarna = parseInt($('#jmlwarna').html());
                    var no = 1;
                    var datawarna = json['datawarna'];
                    $('#bodywarna').html("<tr><td colspan='4' class='text-center'>Tunggu sedang load data..</td></tr>");
                    for (i = 0; i < datawarna.length; i++) {
                        html += '<tr id="image_warna_row' + image_warna_row + '">';
                        html += '<td class="text-center">' + no + '</td>';
                        html += '<td>' + datawarna[i]['warna'] + '</td>';
                        html += '<td><img src="<?php echo URL_PROGRAM ?>assets/image/_small/small_gproduk' + datawarna[i]['gbr'] + '"></td>';
                        html += '<td><button type="button" id="btnhapuswarna' + image_warna_row + '" onclick="hapusWarna(' + image_warna_row + ',' + idproduk + ',' + datawarna[i]['idwarna'] + ')" class="btn btn-danger btn-sm">Hapus</button></td>';
                        html += '</tr>';
                        image_warna_row++;
                        no++;
                    }

                    $('#bodywarna').html(html);

                    $('#hasil').addClass('alert alert-success');
                    $('#idwarna').val('0');
                    $('#produk_image').val('');

                    $('#jmlwarna').html(jmlwarna + 1);
                }
                $('#btngambarwarna').button("reset");
                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });
    }

    function hapusWarna(imagewarnarow, idproduk, idwarna) {
        $('#btnhapuswarna' + imagewarnarow).button("loading");
        var data = "actiondata=hapuswarnagambar&idproduk=" + idproduk + "&idwarna=" + idwarna;

        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: data,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');
                    $('#btnhapuswarna' + imagewarnarow).button("reset");
                    $('#hasil').show();
                    $('#hasil').html(json['result']);
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');
                } else {
                    var jmlwarna = parseInt($('#jmlwarna').html());
                    $('#image_warna_row' + imagewarnarow).remove();

                    $('#jmlwarna').html(jmlwarna - 1);
                }



            }
        });

    }

    function uploadGambarDetail() {
        $('#btngambardetail').button("loading");
        $('#hasil').removeClass();
        $('#hasil').hide();
        var datagambardetail = new FormData(frmdata);
        datagambardetail.append("actiondata", "uploadgambardetail");
        var idproduk = $('#idproduk').val();
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: datagambardetail,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');


                } else {
                    var html = '';
                    var dataimage = json['dataimage'];

                    $('#tbodygbrdetail').html('<tr><td colspan="3" class="text-center">Tunggu sedang memuat data...</td></tr>');
                    for (i = 0; i < dataimage.length; i++) {
                        html += '<tr id="gbr_detail_row' + gbr_detail_row + '">';
                        html += '<td class="text-center"><img src="<?php echo URL_PROGRAM ?>assets/image/_small/small_gproduk' + dataimage[i]['gbr_detail'] + '"></td>';
                        html += '<td class="text-center"><button type="button" id="btnhapusgambardetail' + gbr_detail_row + '" onclick="hapusGambarDetail(' + gbr_detail_row + ',' + idproduk + ',\'' + dataimage[i]['gbr_detail'] + '\')" class="btn btn-danger btn-sm">Hapus</button></td>';
                        html += '</tr>';
                        gbr_detail_row++;
                    }
                    /*
                    html  = '<tr id="gbr_detail_row'+gbr_detail_row+'">';
                    html += '<td class="text-center"><img src="<?php echo URL_PROGRAM ?>assets/image/_small/small_gproduk'+json['image']+'"></td>';
                    html += '<td class="text-center"><button type="button" id="btnhapusgambardetail'+gbr_detail_row+'" onclick="hapusGambarDetail('+gbr_detail_row+','+idproduk+',\''+json['image']+'\')" class="btn btn-danger btn-sm">Hapus</button></td>';
                    html += '</tr>';
                    */
                    //$('#tfootergbrdetail').before(html);
                    $('#tbodygbrdetail').html(html);

                    $('#hasil').addClass('alert alert-success');

                    $('#produk_image_detail').val('');

                }
                $('#btngambardetail').button("reset");
                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });
    }

    function hapusGambarDetail(row, idproduk, image) {
        $('#btnhapusgambardetail' + row).button("loading");
        var data = "actiondata=hapusgambardetail&idproduk=" + idproduk + "&image_detail=" + image;
        $('#hasil').removeClass();
        $('#hasil').hide();
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: data,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');
                    $('#btnhapusgambardetail' + row).button("reset");

                } else {
                    $('#hasil').addClass('alert alert-success');
                    $('#gbr_detail_row' + row).remove();


                }

                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });
    }

    function simpanstokoption() {
        $('#btnstokoption').button("loading");
        $('#hasil').removeClass();
        $('#hasil').hide();

        var idproduk = $('#idproduk').val();
        var idwarna = $('#id_warna').val();
		var nama_warna = $('#id_warna option:selected' ).text();
        var idukuran = $('#id_ukuran').val();
		var nama_ukuran = $('#id_ukuran option:selected' ).text();
        var tambahan_harga = $('#tambahan_harga').val();
        var stok_option = parseInt($('#stok_option').val());
        var allstok = parseInt($('#jml_stok').val());
        var datastok = "actiondata=savestokoption&idproduk=" + idproduk + '&idwarna=' + idwarna + '&idukuran=' + idukuran + '&stok_option=' + stok_option + '&tambahan_harga=' + tambahan_harga;

        if (stok_option == 0) {
            alert('Masukkan Jumlah Stok');
            $('#btnstokoption').button("reset");
            return false;
        }
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: datastok,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');


                } else {
                    var html = '';
                    var datastok = json['datastok'];

                    $('#tbodyoption').html('<tr><td colspan="3" class="text-center">Tunggu sedang memuat data</td></tr>');

                    for (i = 0; i < datastok.length; i++) {
                        hargaoption = parseInt(datastok[i]['tambahan_harga']) + parseInt($('#hrg_jual').val());
                        html += '<tr id="option_row' + option_row + '">';
                        html += '<td >' + datastok[i]['ukuran'] + '</td>';
                        html += '<td>' + datastok[i]['warna'] + '</td>';
                        html += '<td class="text-center"><a href="javascript:void(0)" onclick="formmodaleditstok(' + option_row + ',' + idproduk + ',' + datastok[i]['idukuran'] + ',' + datastok[i]['idwarna'] + ',\'' + datastok[i]['ukuran'] +'\',\''+datastok[i]['warna']+'\')">' + datastok[i]['stok'] + '</a></td>';
                        html += '<td class="text-right">' + datastok[i]['tambahan_harga'] + '</td>';
                        html += '<td class="text-right">' + hargaoption + '</td>';

                        html += '<td><button type="button" id="btnhapusstok' + option_row + '" onclick="hapusStokOption(' + option_row + ',' + idproduk + ',' + datastok[i]['idukuran'] + ',' + datastok[i]['idwarna'] + ',' + datastok[i]['stok'] + ')" class="btn btn-danger btn-sm">Hapus</button></td>';
                        html += '</tr>';
                        option_row++;
                    }

                    $('#tbodyoption').html(html);

                    $('#hasil').addClass('alert alert-success');

                    $('#jml_stok').val(allstok + stok_option);
                }
                $('#btnstokoption').button("reset");
                $('#hasil').show();
				var msg = json['result'] + ' <b class="text-danger">' + nama_warna + ' ' + nama_ukuran + '</b>';
                $('#hasil').html(msg);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });
    }

    function hapusStokOption(row, idproduk, idukuran, idwarna, stok) {
        var allstok = parseInt($('#jml_stok').val());
        $('#btnhapusstok' + row).button("loading");
        var data = 'actiondata=hapusstokoption&idproduk=' + idproduk + '&idwarna=' + idwarna + '&idukuran=' + idukuran + '&stok=' + stok;
        $('#hasil').removeClass();
        $('#hasil').hide();
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: data,
            dataType: 'json',
            success: function(json) {
                if (json['status'] == 'error') {
                    $('#hasil').addClass('alert alert-danger');
                    $('#btnhapusstok' + row).button("reset");
                } else {
                    $('#hasil').addClass('alert alert-success');
                    $('#option_row' + row).remove();
                    $('#jml_stok').val(allstok - stok);
                }

                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    }

    function simpanhargawarnaukuran() {
        $('#btnhargawu').button("loading");

        var idproduk = $('#idproduk').val();
        var idwarna = $('#id_warna_h').val();
        var idukuran = $('#id_ukuran_h').val();
        var tambahan = $('#harga_wu').val();
        var dataharga = "actiondata=savehargatambahan&idproduk=" + idproduk + '&idwarna=' + idwarna + '&idukuran=' + idukuran + '&tambahan_harga=' + tambahan;

        if (idukuran == '' || idukuran == '0') {
            alert('Pilih Ukuran');
            $('#btnhargawu').button("reset");
            return false;
        }
        /*
        if(idwarna == '' || idwarna == '0')
        {
        	alert('Pilih Warna');
        	$('#btnhargawu').button("reset");
        	return false;
        }
        */
        if (tambahan < 1) {
            alert('Masukkan Harga Tambahan');
            $('#btnhargawu').button("reset");
            return false;
        }
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: dataharga,
            dataType: 'json',
            success: function(json) {

                if (json['status'] == 'error') {

                    $('#hasil').addClass('alert alert-danger');


                } else {
                    var html = '';
                    var dataharga = json['dataharga'];

                    $('#tbodyhargatambahan').html('<tr><td colspan="3" class="text-center">Tunggu sedang memuat data</td></tr>');

                    for (i = 0; i < dataharga.length; i++) {
                        html += '<tr id="tambahan_hrg_row' + tambahan_hrg_row + '">';
                        html += '<td >' + dataharga[i]['ukuran'] + '</td>';
                        html += '<td>' + dataharga[i]['warna'] + '</td>';
                        html += '<td class="text-right">' + dataharga[i]['harga'] + '</td>';
                        html += '<td class="text-center"><button id="btnhapustambahharga' + tambahan_hrg_row + '" type="button" class="btn btn-sm btn-danger" onclick="hapusTambahHarga(\'' + tambahan_hrg_row + '\',\'' + idproduk + '\',\'' + dataharga[i]['idukuran'] + '\',\'' + dataharga[i]['idwarna'] + '\')">Hapus</button></td>';
                        html += '</tr>';
                        tambahan_hrg_row++;
                    }

                    $('#tbodyhargatambahan').html(html);

                    $('#hasil').addClass('alert alert-success');
                    /*
                    $('#id_ukuran_h').val('0');
                    $('#id_warna_h').val('0');
                    $('#harga_wu').val('0');
                    */
                }
                $('#btnhargawu').button("reset");
                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });

    }

    function hapusTambahHarga(row, idproduk, idukuran, idwarna) {
        $('#btnhapustambahharga' + row).button("loading");
        var data = 'actiondata=hapustambahharga&idproduk=' + idproduk + '&idwarna=' + idwarna + '&idukuran=' + idukuran;
        $('#hasil').removeClass();
        $('#hasil').hide();
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: data,
            dataType: 'json',
            success: function(json) {
                if (json['status'] == 'error') {
                    $('#hasil').addClass('alert alert-danger');
                    $('#btnhapustambahharga' + row).button("reset");
                } else {
                    $('#hasil').addClass('alert alert-success');
                    $('#tambahan_hrg_row' + row).remove();
                }

                $('#hasil').show();
                $('#hasil').html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    }

    function formmodaleditstok(row, idproduk, idukuran, idwarna,nama_ukuran,nama_warna) {

        var data = '<div class="modal-dialog" style="width:40%">';
        data += '<div class="modal-content"><div class="modal-header">';
        data += '<a class="close" data-dismiss="modal">&times;</a>';
        data += '<h4 class="modal-title">Update Stok</h4>';
        data += '</div>';
        data += '<div class="modal-body">';
        data += '<div id="hasileditstok' + row + '"></div>';
        data += '<input type="hidden" id="row" name="row" value="' + row + '">';
        data += '<input type="hidden" id="eidproduk" name="eidproduk" value="' + idproduk + '">';
        data += '<input type="hidden" id="eidwarna" name="eidwarna" value="' + idwarna + '">';
        data += '<input type="hidden" id="eidukuran" name="eidukuran" value="' + idukuran + '">';
		data += '<input type="hidden" id="enmwarna" name="enmwarna" value="' + nama_warna + '">';
        data += '<input type="hidden" id="enmukuran" name="enmukuran" value="' + nama_ukuran + '">';
        data += '<div class="form-group">';
        data += '<label>Jumlah Stok</label>';
        data += '<input type="text" id="estokop" name="estokop" class="form-control" value="0">';
        data += '</div>';
        data += '<div class="form-group">';
        data += '<a onclick="simpaneditstokoption()" id="btnsimpanstok" class="btn btn-sm btn-primary">Simpan</a>&nbsp;';
        data += '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>';
        data += '</div>';
        data += '<div class="alert alert-warning">Jumlah stok yang dimasukkan akan otomatis ditambah dengan jumlah stok yang sekarang. <br><br> Untuk mengurangi stok silakan gunakan angka minus (-). Misal -1, artinya mengurangi stok sebanyak 1 pcs</div>';
        data += '</div></div></div>';

        $('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function() {
            $(this).remove();
        });
    }

    function simpaneditstokoption() {
        $('#btnsimpanstok').button("loading");

        var row = $('#row').val();
        var idproduk = $('#eidproduk').val();
        var idwarna = $('#eidwarna').val();
        var idukuran = $('#eidukuran').val();
		 var enmwarna = $('#enmwarna').val();
        var enmukuran = $('#enmukuran').val();
        var stok = $('#estokop').val();
        var stoklama = parseInt($('#stok' + row).html());
        var data = 'actiondata=editstok&idproduk=' + idproduk + '&idwarna=' + idwarna + '&idukuran=' + idukuran + '&stok=' + stok + '&nmukuran=' + enmukuran + '&nmwarna=' + enmwarna;
        var stokproduk = $('#jml_stok').val();
        var jmlstok = parseInt(stoklama) + parseInt(stok);
        var jmlallstok = parseInt(stokproduk) + parseInt(stok);

        $('#hasileditstok' + row).removeClass();
        $('#hasileditstok' + row).hide();
        if (stoklama == 0 && stok < 0) {
            $('#hasileditstok' + row).addClass('alert alert-danger');
            $('#hasileditstok' + row).html('Masukkan Stok lebih dari nol');
            $('#hasileditstok' + row).show();
            $('#btnsimpanstok').button("reset");
            return false;
        }
        $.ajax({
            url: '<?php echo URL_PROGRAM_ADMIN . 'view/' . folder . "/action.php" ?>',
            method: "POST",
            data: data,
            dataType: 'json',
            success: function(json) {
				
                if (json['status'] == 'error') {

                    //$('#hasil').addClass('alert alert-danger');
					  $('#hasileditstok' + row).addClass('alert alert-danger');


                } else {

                    $('#stok' + row).html(jmlstok);
                    $('#jml_stok').val(jmlallstok);
                    $('#hasileditstok' + row).addClass('alert alert-success');

                }
				//msg = json['result'] + ' ' + nama_ukuran + ' - ' + nama_warna;
				//alert(msg);
                $('#btnsimpanstok').button("reset");
                $('#hasileditstok' + row).show();
                $('#hasileditstok' + row).html(json['result']);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');

            }
        });


    }
</script>