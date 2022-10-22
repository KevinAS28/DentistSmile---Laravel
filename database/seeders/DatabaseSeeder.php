<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use App\Models\Orangtua;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sekolah;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'email'     => 'admin@admin.com',
            'password'  =>  bcrypt('admin1234'),
            
        ]);

        // $kecamatan = Kecamatan::create([
        //     'nama'     => 'Tambun',
            
        // ]);
        // $kelurahan = Kelurahan::create([
        //     'nama'     => 'Mekarsari',
        //     'id_kecamatan' => 1,
            
        // ]);
        // $dokter = Dokter::create([
        //     'id_users'  => 2,
        //     'nik'       => '123456',
        //     'nama' => 'Indira',
        //     'id_kecamatan' => 1,
        //     'jenis_kelamin' => 'Perempuan',
        //     'tempat_lahir'  => 'Bekasi',
        //     'tanggal_lahir' => '2000-01-01',
        //     'no_telp'   => '088210968646',
        //     'no_str'    => '654321',
            
        // ]);
        // $sekolah = Sekolah::create([
        //     'id_kelurahan'     => '1',
        //     'type' => 'Sekolah',
        //     'nama' => 'SDN Mekarsari 04',
        //     'alamat' => 'Jalan Tambun Raya 04',

            
        // ]);

        // $sekolah = Sekolah::create([
        //     'id_kelurahan'     => '1',
        //     'type' => 'Posyandu',
        //     'nama' => 'Puskesmas Jatibening',
        //     'alamat' => 'Jalan Jatibening 90',

            
        // ]);





    }
}
