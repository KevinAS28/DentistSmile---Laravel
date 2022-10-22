<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\PemeriksaanFisikController;
use App\Http\Controllers\PemeriksaanGigiController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AdminController;



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
Route::group(['middleware' => 'guest'], function () {
Route::get('/', function () {
    return view('auth.login');
});
});
Auth::routes();

Route::get('/list-desa/{id_kecamatan}', [App\Http\Controllers\KelurahanController::class, 'listDesa'])
    ->name('list-desa');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('/list-sekolah/{id_kelurahan}', [App\Http\Controllers\SekolahController::class, 'listSekolah'])
    ->name('list-sekolah');
    Route::get('/list-posyandu/{id_kelurahan}', [App\Http\Controllers\SekolahController::class, 'listPosyandu'])
    ->name('list-posyandu');
    Route::get('/list-kelas/{id_sekolah}', [App\Http\Controllers\SekolahController::class, 'listKelas'])
    ->name('list-sekolah');
    Route::get('/list-kelurahandokter', [App\Http\Controllers\DokterController::class, 'listKelurahan'])
    ->name('list-kelurahandokter');
    Route::get('list-anakdokter', [App\Http\Controllers\DokterController::class, 'listAnak'])
    ->name('list-anakdokter');
    Route::get('list-anak-ukgm', [App\Http\Controllers\DokterController::class, 'listAnakUkgm'])
    ->name('list-anakd-ukgm');
    Route::get('list-anakdokter-rekap', [App\Http\Controllers\DokterController::class, 'listAnakRekap'])
    ->name('list-anakdokter-rekap');
    Route::get('list-anak/{anak}', [App\Http\Controllers\PemeriksaanFisikController::class, 'listAnak'])
    ->name('list-anak');

// Route::post('/dokter',[DokterController::class,'store']);
Route::get('/home',function(){
    return view('home');
})->name('home');

Route::group(['prefix' => 'admin'], function () {
  Route::group(['middleware' => ['auth','ceklevel:admin']], function () {
    Route::resource('dokter', DokterController::class)->except('destroy');
    Route::resource('orangtua', OrangtuaController::class)->except('destroy');
    Route::resource('anak', AnakController::class)->except('destroy');
    Route::resource('kecamatan', KecamatanController::class)->except('destroy');
    Route::resource('kelurahan', KelurahanController::class)->except('destroy');
    Route::resource('sekolah', SekolahController::class)->except('destroy');
    Route::resource('posyandu', PosyanduController::class)->except('destroy');
    Route::resource('artikel', ArtikelController::class)->except('destroy');
    Route::resource('video', VideoController::class)->except('destroy');
    Route::resource('admin', AdminController::class)->except('destroy','index');
    Route::get('/data-admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('/dashboard',function(){return view('admin.dashboard.dashboard');
    });
    Route::get('/kelas/{id}',[App\Http\Controllers\SekolahController::class,'viewKelas'])->name('viewKelas');
  });
});
Route::post('/daftarUser', [App\Http\Controllers\Auth\RegisterController::class, 'storeOrangtua'])
    ->name('storeOrangtua');

Route::post('/register-user',[OrangtuaController::class, 'registerUser'])->name('registeruser');

Route::group(['prefix' => 'orangtua'], function () {
  Route::group(['middleware' => ['auth','ceklevel:orangtua']], function () {
    Route::resource('pemeriksaanfisik', PemeriksaanFisikController::class)->except('destroy');
    Route::get('/dashboard',[OrangtuaController::class,'viewDashboard'])->name('viewDashboard.orangtua');
    Route::get('/anak',[OrangtuaController::class,'viewAnak'])->name('viewanak');
    Route::get('/anak/create',[OrangtuaController::class,'viewTambahAnak'])->name('view-anak.create');
    Route::post('/anak/store',[OrangtuaController::class,'tambahAnak'])->name('tambahanak.store');
    Route::get('/anak/{id}/edit',[OrangtuaController::class,'editAnak'])->name('orangtua-anak.edit');
    Route::put('/anak/{id}/update',[OrangtuaController::class,'updateAnak'])->name('orangtua-anak.update');
    Route::resource('pemeriksaangigi', PemeriksaanGigiController::class)->except('destroy','create');
    Route::get('/pemeriksaan/riwayat',[PemeriksaanFisikController::class,'riwayat'])->name('view-riwayat');
    Route::get('/profil',[OrangtuaController::class,'profil'])->name('orangtua.profil');
    Route::post('/updateprofil',[OrangtuaController::class,'updateProfil'])->name('orangtua.updateprofil');
    Route::get('periksa-gigi',[PemeriksaanGigiController::class,'create'])->name('periksa-gigi.create');
    Route::get('/reservasi/{id}',[OrangtuaController::class,'reservasi'])->name('reservasi');
    });
  });

Route::group(['prefix' => 'admin/table'], function () {
    Route::get('/data-dokter',[DokterController::class, 'data'])->name('dokter.table');
    Route::get('/data-orangtua',[OrangtuaController::class, 'data'])->name('orangtua.table');
    Route::get('/data-anak',[AnakController::class, 'data'])->name('anak.table');
    Route::get('/data-kecamatan',[KecamatanController::class, 'data'])->name('kecamatan.table');
    Route::get('/data-kelurahan',[KelurahanController::class, 'data'])->name('kelurahan.table');
    Route::get('/data-sekolah',[SekolahController::class, 'data'])->name('sekolah.table');
    Route::get('/data-pemeriksaan-fisik',[PemeriksaanFisikController::class,'riwayatfisik'])->name('riwayat-fisik');
    Route::get('/data-pemeriksaan-mata',[PemeriksaanFisikController::class,'riwayatmata'])->name('riwayat-mata');
    Route::get('/data-pemeriksaan-telinga',[PemeriksaanFisikController::class,'riwayattelinga'])->name('riwayat-telinga');
    Route::get('/data-pemeriksaan-gigi',[PemeriksaanFisikController::class,'riwayatgigi'])->name('riwayat-gigi');
    Route::get('/data-posyandu',[PosyanduController::class, 'data'])->name('posyandu.table');
    Route::get('/data-artikel',[ArtikelController::class,'data'])->name('artikel.table');
    Route::get('/data-kelas/{id}',[KelasController::class,'data'])->name('kelas.table');
    Route::get('/data-video',[VideoController::class,'data'])->name('video.table');
    Route::get('/data-admin',[AdminController::class,'data'])->name('admin.table');



    });
Route::group(['prefix' => 'orangtua/table'], function () {

  Route::get('/data-anak',[OrangtuaController::class, 'dataAnak'])->name('anak-orangtua.table');


  });

Route::group(['prefix' => 'delete'], function () {
  Route::get('dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy');
  Route::get('orangtua/{id}', [OrangtuaController::class, 'destroy'])->name('orangtua.destroy');
  Route::get('kecamatan/{id}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
  Route::get('/kelurahan/{id}',[KelurahanController::class, 'destroy'])->name('kelurahan.destroy');
  Route::get('sekolah/{id}', [SekolahController::class, 'destroy'])->name('sekolah.destroy');
  Route::get('/orangtua-anak/{id}',[OrangtuaController::Class,'deleteAnak'])->name('orangtua-anak.destroy');
  Route::get('/posyandu/{id}', [PosyanduController::class, 'destroy'])->name('posyandu.destroy');
  Route::get('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
  Route::get('/video/{id}', [VideoController::class, 'destroy'])->name('video.destroy');
  Route::get('admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

});

Route::group(['prefix' => 'dokter'], function () {
  Route::group(['middleware' => ['auth','ceklevel:dokter']], function () {
    Route::get('/dashboard',[DokterController::class,'viewDashboard'])->name('dokter.dashboard');
    Route::get('/profil',[DokterController::class, 'profil'])->name('dokter.profil');
    Route::post('/profil/edit/{id}',[DokterController::class, 'profil_edit'])->name('dokter.profilEdit');
    Route::post('/profil/{id}',[DokterController::class, 'profil_update'])->name('dokter.profilUpdate');
    Route::get('/pemeriksaan-ukgs',[DokterController::class, 'pemeriksaan_ukgs'])->name('dokter.periksaUKGS');
    Route::get('/pemeriksaan-ukgs/sekolah',[DokterController::class, 'dropdown_sekolah_ukgs'])->name('dokter.sekolahUKGS');
    Route::get('/pemeriksaan-ukgs/kelas',[DokterController::class, 'dropdown_kelas_ukgs'])->name('dokter.kelasUKGS');
    //Route::post('/pemeriksaan-ukgs/fetch',[DokterController::class, 'dropdown_wilayah_ukgs'])->name('dokter.periksaUKGS.fetch');
    Route::get('/pemeriksaan-ukgm',[DokterController::class, 'pemeriksaan_ukgm'])->name('dokter.periksaUKGM');
    Route::get('/pemeriksaan-ukgs/pemeriksaan-data/{id}',[DokterController::class, 'pemeriksaan_data_ukgs'])->name('dokter.pemeriksaanDataUKGS');
    Route::get('/pemeriksaan-ukgm/pemeriksaan-data/{id}',[DokterController::class, 'pemeriksaan_data_ukgm'])->name('dokter.pemeriksaanDataUKGM');
    Route::post('pemeriksaan-data-ukgm',[DokterController::class, 'storeSkriningGigiUkgm'])->name('dokter.storePemeriksaanDataUkgm');
    Route::post('pemeriksaan-data-ukgs',[DokterController::class, 'storeSkriningGigiUkgs'])->name('dokter.storePemeriksaanDataUkgs');
    Route::get('/rekap-ukgs',[DokterController::class, 'rekap_ukgs'])->name('dokter.rekapDataUKGS');
    Route::get('/rekap-ukgm',[DokterController::class, 'rekap_ukgm'])->name('dokter.rekapDataUKGM');
    Route::get('/rekap-ukgs/rekap-datail-ukgs',[DokterController::class, 'rekap_detail_ukgs'])->name('dokter.rekapDetailUKGS');
    Route::get('/rekap-ukgs/rekap-datail-ukgs/{id}',[DokterController::class, 'rekap_detail_ukgs_id'])->name('dokter.rekapDetailUKGSID');
    Route::get('/rekap-ukgm/rekap-data-ukgm',[DokterController::class, 'rekap_detail_ukgm'])->name('dokter.rekapDetailUKGM');
    Route::get('/hasil-gambar/{id}/{filename}',[DokterController::class, 'lihat_gambar'])->name('dokter.lihat_gambar');
    Route::get('re-check-ai',[App\Http\Controllers\PemeriksaanGigiController::class,'rePemeriksaanAi']);
    });
  });

  Route::group(['middleware' => ['auth','ceklevel:dokter,admin']], function () {
    Route::resource('artikel', ArtikelController::class)->except('destroy');
    Route::resource('video', VideoController::class)->except('destroy');
  });



