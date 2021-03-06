<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['prefix' => 'pengajuan'], function ()
{
    Route::get('{kode_surat}', 'SuratController@formPengajuan')->name('form_surat');
    Route::post('ajukan', 'SuratController@ajukan')->name('ajukan_surat');
});

Route::middleware('auth')->group(function ()
{
    Route::get('/', 'AdminController@index')->name('beranda');
    Route::group(['prefix' => 'surat', 'as' => 'surat.'], function ()
    {
        Route::get('/', 'AdminController@semua')->name('riwayat');
        Route::group(['prefix' => '{id}'], function ()
        {
            Route::get('/', 'SuratController@detail')->name('detail');
            Route::get('cetak', 'SuratController@cetak')->name('cetak');
            Route::get('sunting', 'AdminController@sunting')->name('sunting');
            Route::put('sunting', 'SuratController@sunting')->name('edit');
            Route::delete('/', 'SuratController@hapus')->name('hapus');
        });
    });
    Route::group(['prefix' => 'laporan', 'as' => 'laporan.'], function ()
    {
        Route::get('/', 'AdminController@laporan')->name('umum');
    });
    Route::group(['prefix' => 'pengaturan', 'as' => 'pengaturan.'], function ()
    {
        Route::get('/', 'AdminController@pengaturanUmum')->name('umum');
    });
});

Route::group(['prefix' => 'data-surat', 'as' => 'data_surat.'], function ()
{
    Route::get('semua', 'SuratController@semua')->name('semua');
    Route::get('terbaru', 'SuratController@terbaru')->name('terbaru');
});
