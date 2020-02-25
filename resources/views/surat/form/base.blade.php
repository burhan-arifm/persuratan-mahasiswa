@extends('layouts.app')

@section('page-title')
	Pengajuan Surat @yield('page-name')
@endsection

@section('content')
    <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<ul class="nav menu">
			<li class="parent "><a data-toggle="collapse" href="#pen_surat">
				<em class="fa fa-envelope">&nbsp;</em> Pengajuan Surat <span data-toggle="collapse" href="#pen_surat" class="icon pull-right"><em class="fa fa-arrow-circle-down"></em></span>
				</a>
				<ul class="children collapse" id="pen_surat">
                    <!-- <li><a href="/pengajuan-surat/spmk"> -->
                    <li><a href="{{ route('form_surat', ['kode_surat' => 'spmk']) }}">
                    <span class="fa fa-envelope-open">&nbsp;</span>Masih Kuliah</a></li>
                    <li><a href="/pengajuan-surat/izin-observasi">
                    <span class="fa fa-envelope-open">&nbsp;</span>Izin Observasi</a></li>
                    <li><a href="/pengajuan-surat/izin-riset">
                    <span class="fa fa-envelope-open">&nbsp;</span>Izin Riset</a></li>
                    <!-- <li><a href="/pengajuan-surat/izin-kunjungan"> -->
                    <li><a href="{{ route('form_surat', ['kode_surat' => 'izin-kunjungan']) }}">
                    <span class="fa fa-envelope-open">&nbsp;</span>Izin Kunjungan</a></li>
                    <li><a href="/pengajuan-surat/praktik-mata-kuliah">
                    <span class="fa fa-envelope-open">&nbsp;</span>Praktik Mata Kuliah</a></li>
                    <li><a href="/pengajuan-surat/job-training">
                    <span class="fa fa-envelope-open">&nbsp;</span>Job Training</a></li>
                    <li><a href="/pengajuan-surat/ppm">
                    <span class="fa fa-envelope-open">&nbsp;</span>PPM</a></li>
                    <li><a href="/pengajuan-surat/surat-keterangan">
                    <span class="fa fa-envelope-open">&nbsp;</span>Surat Keterangan</a></li>
                    <li><a href="/pengajuan-surat/permohonan-munaqasah">
                    <span class="fa fa-envelope-open">&nbsp;</span>Munaqasah</a></li>
                    <li><a href="/pengajuan-surat/komprehensif">
                    <span class="fa fa-envelope-open">&nbsp;</span>Komprehensif</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<!--sidebar-->

	<div id="app" class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li>
					<a href="#"><em class="fa fa-home"></em></a>
				</li>
				<li class="active">Pengajuan Surat</li>
				<li class="active">@yield('page-name')</li>
			</ol>
		</div><!--sitemap-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Form Pengajuan Surat @yield('page-name')</h1>
			</div>
		</div><!--header-->
		
		<div class="panel panel-container">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="alert bg-danger" role="alert"><em class="fas fa-exclamation-triangle">&nbsp;</em> Perhatian! Penulisan Instansi tujuan harus jelas. </em></div>
							<div>Isi form dibawah ini secara lengkap.</div>
							<div>
								<div class="panel-body">
									<form id="pengajuan-surat" class="form-horizontal" action="{{ route('ajukan_surat') }}" method="post">
										<fieldset>
											@csrf
											@yield('main')
											<!-- Form actions -->
											<div class="form-group">
												<div class="col-md-6 widget-right">
													<button type="submit" class="btn btn-primary btn-md pull-right">Submit</button>
												</div>
												<div class="col-md-6 widget-right">
													<button type="reset" class="btn btn-danger btn-md pull-left">Reset</button>
												</div>
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--body content-->
	</div>
	<!--content-->
@endsection