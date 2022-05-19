<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?php echo $config_deskripsitag ?>" />
	<meta name="keywords" content="<?php echo $config_keywordtag ?>" />
	<meta name="author" content="www.goetnik.com" />
	<meta name="Robots" content="index,follow">
	<meta name="googlebot" content="all, index, follow">
	<link rel="image_src" href="<?php echo $gambarshare ?>" />
	<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo URL_PROGRAM . 'favicon.ico' ?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL_PROGRAM . 'favicon.ico' ?>" />

	<title><?php echo $config_jdlsite ?> | <?php echo $config_slogansite ?></title>
	<link href="<?php echo URL_THEMES ?>assets/jqueryui/jquery-ui.min.css" rel="stylesheet">
	<link href="<?php echo URL_THEMES ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo URL_THEMES ?>assets/css/nibrasabu.css" rel="stylesheet">
	<link href="<?php echo URL_THEMES ?>assets/css/owl.carousel.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="<?php echo URL_THEMES ?>assets/css/animate.css" rel="stylesheet">
	<link href="<?php echo URL_THEMES ?>assets/css/colorbox.css" rel="stylesheet">
</head>

<body>
	<input type="hidden" id="url_web" value="<?php echo URL_PROGRAM ?>">
	<header>
		<div class="container-fluid nav-atas">

			<div class="container">
				<div class="float-right">
				<?php if ($idmember == '') { ?>
					<a href="<?php echo URL_PROGRAM . 'login' ?>" class="btn btn-success mb-1 mt-1">Login</a>
					<a href="<?php echo URL_PROGRAM . 'daftar' ?>" class="btn btn-info mb-1 mt-1" >Daftar</a>	
							
							
						<?php } else { ?>
						<ul class="nav">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Data Belanja Saya</a>
								<div class="dropdown-menu list-lastorder">
									<div class="container">
										<div class="row">
											<?php

												if ($datapesanan) {
													foreach ($datapesanan as $order) {
														?>
													<div class="col-md-12 list-lastorder-item">
														<div class="row">
															<div class="col-md-6">
																#<?php echo $order['pesanan_no'] ?><br>
																<small><?php echo $order['pesanan_tgl'] ?></small>
															</div>
															<div class="col-md-6 text-right">
																<?php
																			$total = ($order['pesanan_subtotal'] + $order['pesanan_kurir']) - $order['dari_poin'] - $order['potongan_kupon'] - $order['dari_deposito'];
																			echo 'Rp .' . $dtFungsi->fuang($total);
																			?>
															</div>
														</div>
													</div>
												<?php
														}
														?>

											<?php
												}
												?>
											<div class="col-md-12">
												<a id="button-allorder" href="<?php echo URL_PROGRAM . 'orderhistory' ?>" class="btn btn-info btn-block">Selengkapnya</a>
											</div>
										</div>
									</div>
								</div>
							</li>

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle fa-lg" aria-hidden="true"></i>Data Akun</a>

								<div class="dropdown-menu">
									<div class="px-0 container">

										<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'account' ?>">Biodata</a>
										<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'poin' ?>">Data Poin</a>
										<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'saldo' ?>">Data Saldo</a>
										<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'list-produk' ?>">Cek Stok Produk</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'account/?keluar' ?>">Keluar</a>
									</div>
								</div>

							</li>
						</ul>
						<?php } ?>
					
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-4 strip-orange"></div>
				<div class="col-4 strip-pink"></div>
				<div class="col-4 strip-ungu"></div>

			</div>
		</div>
		<div class="clearfix"></div>
		<div class="container">
			<div class="row">
				<div class="col-8">
					<div class="row">
						<div class="col-12">
							<h1 class="text-logo wow bounce" data-wow-delay=".3s" title="<?php $config_jdlsite ?>"><a href="<?php echo URL_PROGRAM ?>"><?php echo ucfirst(stripslashes($config_namatoko)) ?></a></h1>
							<h2 class="text-slogan">
								<?php if ($slogan) { ?>
									<?php echo $slogan[0] ?> <span><?php echo isset($slogan[1]) ? $slogan[1] : '' ?></span>
								<?php } ?>
							</h2>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-4 plat-cart">

					<a id="cart-btn">
						<small id="cart-count">
							<?php if ($jmlitem > 0) { ?>
								<?php echo $jmlitem . ' item - Rp. ' . $dtFungsi->fuang($zsubtotal) ?>
							<?php } else { ?>
								Rp. 00,0
							<?php } ?>
						</small>
						<i class="fa fa-shopping-basket" aria-hidden="true"></i>
					</a>
					<div class="list-cart">
						<?php if ($jmlitem > 0) { ?>
							<div class="col-md-12">
								<a href="<?php echo URL_PROGRAM . 'cart' ?>" class="btn btn-outline-danger btn-block"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Beli</a>
							</div>
							<?php foreach ($hcart as $cart) { ?>
								<div class="col-md-12 list-cart-header">
									<div class="row">
										<div class="col-4">
											<img class="img-fluid mx-auto" src="<?php echo URL_IMAGE . '_thumb/thumbs_gproduk' . $cart['image_produk'] ?>">
										</div>
										<div class="col-8 text-right">
											<h3 class="produk-cart-header"><?php echo $cart['product'] ?></h3>
											<small class="atribut-cart-head"><?php echo $cart['warna_nama'] != '' ? 'Warna : ' . $cart['warna_nama'] : '' ?>, <?php echo $cart['ukuran_nama'] != '' ? 'Ukuran : ' . $cart['ukuran_nama'] : '' ?></small><br>
											<small class="atribut-cart-head"><b><?php echo $cart['qty'] . ' x Rp. ' . $dtFungsi->fuang($cart['harga']) ?></b></small>
										</div>
									</div>
								</div>
							<?php } ?>

							<br>
						<?php } else { ?>
							<p class="text-center">Tidak Ada Pesanan</p>
						<?php } ?>
					</div>

				</div>
			</div>

		</div>

		<nav class="navbar navbar-expand-lg navbar-light" id="menu">
			<div class="container">
				<div class="caption-menu">Menu</div>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-utama" aria-controls="menu-utama" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="menu-utama">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo URL_PROGRAM ?>">Home</a>
						</li>
						<?php if ($kategori) { ?>

							<?php foreach ($kategori as $kat) { ?>
								<?php if ($kat['children']) { ?>
									<li class="nav-item dropdown">
										<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $kat['kategori_nama']; ?></a>
										<ul class="dropdown-menu menulist" aria-labelledby="navbarDropdownMenuLink">
											<?php foreach ($kat['children'] as $child) { ?>
												<?php $childs = $dtKategori->getKategori($child['id']) ?>
												<?php if ($childs) { ?>
													<li class="dropdown-submenu">
														<a class="dropdown-item dropdown-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $child['nama']; ?></a>
														<ul class="dropdown-menu dropdownsubmenulist">
															<?php foreach ($childs as $ch) { ?>
																<li><a class="dropdown-item" href="<?php echo URL_PROGRAM . $ch['kategori_alias'] ?>"><?php echo $ch['kategori_nama']; ?></a></li>
															<?php } ?>
														</ul>
													</li>
												<?php } else { ?>
													<li><a class="dropdown-item" href="<?php echo URL_PROGRAM . $child['alias'] ?>"><?php echo $child['nama']; ?></a></li>
												<?php } ?>

											<?php } ?>
										</ul>

									</li>
								<?php    } else { ?>
									<li><a class="nav-link" href="<?php echo URL_PROGRAM . $kat['alias'] ?>"><?php echo $kat['nama'] ?></a></li>
								<?php    } ?>
							<?php } ?>
						<?php } ?>
						<?php if ($menuinformasi) { ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Info</a>
								<div class="dropdown-menu">
									<div class="row">
										<div class="col-md-12 col-md-x">
											<a class="dropdown-item" href="<?php echo URL_PROGRAM . 'list-produk' ?>">Cek Stok Produk</a>
											<?php foreach ($menuinformasi as $menu) { ?>
												<a class="dropdown-item" href="<?php echo URL_PROGRAM . $menu['al'] ?>"><?php echo $menu['nm']; ?></a>
											<?php   } ?>

										</div>
									</div>
								</div>
							</li>
						<?php } ?>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo URL_PROGRAM ?>konfirmasi">Konfirmasi Pembayaran</a>
						</li>
					</ul>
					<form class="form-inline my-2 my-lg-0" action="<?php echo URL_PROGRAM ?>" method="GET">

						<div class="input-group">

							<input type="text" class="form-control" placeholder="Pencarian" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : '' ?>">
							<div class="input-group-prepend">
								<button class="btn btn-search" type="submit" id="button-search"><i class="fa fa-lg fa-search"></i></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</nav>
	</header>
	<div class="container">
		<div class="col-md-12">
			<?php if ($idmember != '') { ?>
				<h4 class="grup_member">Anda Login Sebagai <span class="badge badge-pill badge-info"><?php echo $grup_nama ?></span></h4>
			<?php } ?>
		</div>
	</div>