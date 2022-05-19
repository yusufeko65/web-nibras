<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>

    <div class="widget-box">
        <div class="widget-title"><span class="icon"><i class="icon-file"></i></span>
            <h5>FORM <?php echo strtoupper($judul) ?></h5>
        </div>
        <div class="widget-content nopadding">
            <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>" autocomplete="off">

                <input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
                <input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">

                <div id="hasil" style="display: none;"></div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tabketerangan" aria-controls="tabketerangan" role="tab" data-toggle="tab">Keterangan Kategori</a></li>
                    <?php if ($modul == 'ubah') { ?>
                        <!--<li role="presentation"><a href="#tabfotowarna" aria-controls="tabfotowarna" role="tab" data-toggle="tab">Foto Warna</a></li>-->
                    <?php
                    } ?>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabketerangan">
                        <div class="well">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Kategori</label>
                                <div class="col-sm-4">
                                    <input type="text" id="kategori_nama" name="kategori_nama" class="form-control" value="<?php echo strip_tags($kategori_nama) ?>" size="40">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Induk</label>
                                <div class="col-sm-4">
                                    <input type="text" id="kategori" name="kategori" class="form-control forms" autocomplete="off" value="<?php echo strip_tags($namakat['name']) ?>" <?php //echo $lock 
                                                                                                                                                                                        ?>>
                                    <input type="hidden" id="kategori_induk" name="kategori_induk" value="<?php echo $kategori_induk ?>" class="form-control forms">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Ukuran</label>
                                <div class="col-sm-6">
                                    <input type="text" placeholder="Masukkan Nama Ukuran" id="ukuran_nama" name="ukuran_nama" class="form-control forms" autocomplete="off">
                                    <div id="ukuran-category" class="well well-sm" style="height:80px; overflow: auto;cursor:pointer">
                                        <?php if ($dataukurans) { ?>
                                            <?php foreach ($dataukurans as $ukuran) { ?>
                                                <div id="ukuran-category<?php echo $ukuran['idukuran'] ?>">
                                                    <div class="item-kat"><i class="icon-minus-sign"></i> <?php echo $ukuran['ukuran'] ?></div>
                                                    <input type="hidden" id="idukuran<?php echo $ukuran['idukuran'] ?>" name="idukuran[]" value="<?php echo $ukuran['idukuran'] ?>" class="form-control forms">
                                                </div>
                                            <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File</label>
                                <div class="col-sm-4">
                                    <input name="filelogo" type="file" id="filelogo">
                                    <input type="hidden" value="<?php echo $kategori_image ?>" id="filelama" name="filelama">
                                    <br>
                                    <?php if ($kategori_image != '') { ?>
                                        <img id="image_logo" src="<?php echo URL_IMAGE . '_other/other_small' . $kategori_image ?>">
                                    <?php
                                    } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea cols="80" class="form-control" id="keterangan" name="keterangan"><?php echo trim(stripslashes(html_entity_decode($keterangan))) ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alias URL</label>
                                <div class="col-sm-4">
                                    <input type="text" id="kategori_alias" name="kategori_alias" class="form-control" value="<?php echo $kategori_aliasurl ?>" size="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Sort Order</label>
                                <div class="col-sm-4">
                                    <input type="text" id="kategori_urutan" name="kategori_urutan" class="form-control" value="<?php echo $kategori_urutan ?>" size="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tipe</label>
                                <div class="col-sm-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="spesial" id="spesial" value="1" <?php echo $kategori_spesial == '1' ? 'checked' : '' ?>> Spesial
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                Tipe Spesial <Cite>Hanya untuk kategori yang tidak memiliki anak kategori</cite>
                            </div>
                        </div>
                    </div>
                    <!--
						<div role="tabpanel" class="tab-pane" id="tabfotowarna">
							<div class="well">
								<div class="form-group">
									<label class="col-sm-3 control-label">Warna</label>
									<div class="col-sm-5">
										<select class="form-control input-sm" data-placeholder="Pilih Warna..." tabindex="1" name="idwarna" id="idwarna" style="width:200px">
											<?php  ?>
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
							</div>
						</div>
						-->

                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
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
<script>
    var action = $('#frmdata').prop('action');
    $(function() {
        $("#frmdata").submit(function(event) {
            event.preventDefault();
            simpandata();
        });

        $('#keterangan').summernote({
            height: "300px"
        });

        $("#idwarna").chosen({
            no_results_text: "Tidak Ada Warna!",
            width: "100%"
        });
        /* autocomplete */
        $('#kategori').autocomplete({
            delay: 0,
            source: function(request, response) {
                $.ajax({
                    url: action,
                    dataType: "json",
                    data: {
                        loads: 'kategori',
                        cari: request.term,
                        id: $('#iddata').val()
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
                $('#kategori').val(ui.item.value);
                $('#kategori_induk').val(ui.item.kode);

                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });

        /* @end autocomplete */

        /* autocomplete ukuran */
        $('#ukuran_nama').autocomplete({
            delay: 0,
            source: function(request, response) {
                $.ajax({
                    url: action,
                    dataType: "json",
                    data: {
                        loads: 'ukuran',
                        cari: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name,
                                value: item.name,
                                kode: item.ukuran_id,

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

                $('input[name=\'idukuran\']').val('');
                $('#ukuran-category' + ui.item.kode).remove();
                $('#ukuran-category').append('<div id="ukuran-category' + ui.item.kode + '"><div class="item-kat"><i class="icon-minus-sign"></i> ' + ui.item.label + '<input type="hidden" id="idukuran' + ui.item.kode + '" name="idukuran[]" value="' + ui.item.kode + '" /></div></div>');
                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });

        /* @end autocomplete */
        $('#ukuran-category').delegate('.item-kat', 'click', function() {
            $(this).parent().remove();
        });
    });

    function simpandata() {
        var rv = true;
        $('#btnsimpan').button("loading");
        $('#hasil').removeClass();
        var data = new FormData(frmdata);

        if (rv) {
            $.ajax({
                url: action,
                method: "POST",
                data: data,
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

                            $('#frmdata')[0].reset();
                        } else {
                            if (json['image'] != '') {
                                $('#image_logo').attr('src', '<?php echo URL_PROGRAM ?>assets/image/_other/other_small' + json['image']);
                                $('#filelama').val(json['image']);
                            }
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
        var idkategori = $('#iddata').val();
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
</script>