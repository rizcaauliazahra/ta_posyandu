<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Child;
use App\Models\Measurement;
use App\Repositories\MeasurementRepository;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImportPdfDataSeeder extends Seeder
{
    public function run(MeasurementRepository $repository)
    {
        $block1 = explode("\n", trim("
1 1371006803266078 HAURA PUTRI AZABIAH P
2 1371004101266261 BY.NY SYIFA ADELIA P
3 1371006303268318 ANINDYA MAINDRA P
4 1371005603269486 KHANZA JUWITA RAMADINI P
5 1371000803264559 BY.NY ISNEN QORI L
6 1371001503269240 BY.NY HAJRA RAMANDA L
7 1371001103262900 BY.NY RAHMIATI L
1 1371002412258593 NADEM SYAHRIL RAHMAN L
2 1371001612254237 ARZENO SABRI MAULANA L
3 1371001612255477 BY.NY DOLLA MARGELITA L
4 1371005212255914 AQEELA ZAHWA QANITA P
5 1371000512253668 REYNAND RAFARDHAN ANYARAL
1 1371006909255914 VIOLIN SHAQUEENA AKBAR P
2 1371000309255521 FARIS HADI MIAKKAWI L
3 1371004909251737 AISHA MIFTAHUL JANNAH P
4 1371084909250003 AQELLA ZAYYANA P
5 1371000709254888 ARIF MUHAMMAD ALFA RIZKI L
6 1371000509257078 REISHAKA MAULANA KHAMI L
7 1371000409258630 SAFIK RAZKA PRATAMA L
1 1371003006253289 ALVARO JUNIO KURNIAWAN L
2 1371002906258719 KENANDRA EVANDER SATRIA L
3 1371002506253809 BY.NY FITRI HANYANI L
4 1371005306251591 FATHIA KHAIRA MUJADDIDAH P
5 1371001106259327 AL FATIH KALANDRA HAMID L
6 1371004506253105 FATHIA NAFASYA ILHAM P
7 1371001006251187 KEYSAN ZAFER ZAIN L
1 1371006612246723 BY.NY ARITA FENI P
2 1371000312248014 AZKARA GHAFI PRATAMA L
3 1371084312240001 ALIIYA DZAKIRA P
4 1371001212249061 FIKRI DEFRIZAL L
5 1371082812230001 HISYAM UBAIDILLAH L
6 1371002412237278 QUEENZHA AMEENA FEBRIOLA L
7 1371005112236426 MIFTHAHUL USNAH P
8 1371001412235016 MUHAMMAD SHOLAHUDDIN ERDOGAN L
9 1371000512239677 ARCECTIO RAYYAN AL FARIZKI L
10 1371114612230002 AURELIA PUTRI L
11 1371003012227245 DELVAN ARDIAN PRATAMA L
12 1371002312222548 ARFANDRA DESTRIAN AGRI L
13 1371086012220002 AIRIN PUTRI DEWITA P
14 1371086912220002 SYAHIDAH SALSABILA P
15 1371082412220001 DEVAN ADRIAN PRATAMA L
16 1371001412224479 GIBRAN ARSHAKA.K L
17 1371001912229460 M.FAAD ALRAUF L
18 1371131220220000 RASYA M.ATHAYA L
19 1371085207220003 ASHEETA HAADIYA NDOZHA P
20 1371080212220002 FILLIO CHAFANO SASKARA P
21 1371004712226466 RAISA HAFIZAH P
22 1371002312211953 KENZI MAHARDIKA NEFRI L
23 1371002112216946 ALFATHAN KURNIA PUTRA L
24 1371081212210001 FATHURRAHMAN AL FARUQ L
25 1371001412216431 FADRAN ABQARI TRINADI L
26 1371080212210002 QIYYAM AL HAZEN L
27 1371004112219032 ADINDA SHABIAH P
1 1371002406242935 BY NY RITA EVALIA L
2 1371006206241974 NAJWA ARSYILA AZZALIA P
3 1371001806247442 HANIF AKBAR MUSTOFA L
4 1371001106242076 SYAHAN L
5 1371000606242594 IBRAHIM ZAHEER AL RIDWAN L
6 1371000606242670 ARSHAKA ATHARAZKA L
7 1371005106249880 HARUMMI NUR ARIESTY P
8 1371006006237277 LOVELY YURIHANA P
9 2762300000000000 ADISTI CLARA P
10 1371001506239073 M.ADRIEL AL FATTA L
11 1371005106234981 HAFIZAH P
12 1371001606235365 IBRAHIM L
13 1371000606236446 MUHAMMAD TRISTAN RAHMAN L
14 1371002006226681 SHADIQ ALFARIZQI L
15 1371081706220001 BIHAN MALIQ RAHMADI L
16 1371234000000000 JENAIRA P
17 1371000906221762 RAFANDRA ARKANZA GANIM L
18 1371000206226926 MUHAMMAD FAREZTA YUANDA L
19 1371004306222184 QAIRIN AFIFAH P
20 1371002806211202 ZAFRAN BRILIAN L
21 1371082906210001 M. AL FATHAN JUANDOFA L
22 1371005706213094 KHALISA ADREENA SHAZFA P
23 1371005606213476 ARUMI NASHA P
24 1371001506215539 M. ALZAM L
25 1371085806210001 ADIBA MALAIKA HENDRA P
26 1371001006218129 ALZIO RAFANZA HAMIZAN L
27 1371000506211494 MAHARGA SABRI MAULANA L
28 1371085906210001 HAZETANIA ELYSYA P
29 1371081006210002 M.HANAN AL HAFIDZ L
30 0406202112000000 HAFIFAH RIZKIA PUTRI DODI P
31 1371004106214122 FAIZAH P
"));

        $block2 = explode("\n", trim("
2026-03-28 3 48 VELLA ZUHERNI
2026-03-27 3400 47 SYIFA ADELIA
2026-03-23 2500 46 Maisya lestri
2026-03-16 2700 49 Tiara nur arwanani
2026-03-08 2400 48 Isnen qori
2026-03-15 2458 47 Hajra ramanda
2026-03-11 3200 49 Rahmiati
2025-12-24 2.7 48 Sri wahyuni wulansari
2025-12-16 3.1 49 Tuti riswana
2025-12-16 2.9 49 DOLLA MARGELIA
2025-12-12 2.9 49 SUNDARI FITRI NATASYA
2025-12-05 2.6 47 SINTIA SYAFTA
2025-09-29 2.8 48 SELFI SAFTA
2025-09-03 3.9 50 FATMAWATI
2025-09-09 2.6 47 HIDAYATUL HASNI
2025-09-09 2.4 45 Dian musadayan
2025-09-07 3.9 49 SRI WAHYULI
2025-09-05 3.4 48 KHEKE FAULINA NATHASA
2025-09-04 3.2 49 SRI WAHYUNI
2025-06-30 3 49 mela anggraini
2025-06-29 26 49 yuni herawati
2025-06-25 3.1 50 FITRI HANYANI
2025-06-13 3.2 49 novra winardi
2025-06-11 2.7 47 VIONA ARDIYANTY
2025-06-05 2.8 47 NETTI
2025-06-10 3100 46 GUSNIDA
2024-12-26 2.9 49 Arita feni
2024-12-03 2.8 48 TIO RAMADHAN
2024-12-03 2.8 48 ENDANG
2024-12-12 3.7 51 NIKE SATRIA
2023-12-28 3 49 YUSUF.K
2023-12-24 2.7 47 GUSRA FEBRIANTO
2023-12-11 2100 46 Gemi
2023-12-14 3 49 silmaiyuni
2023-12-05 2.6 48 WAHYU EKA PUTRA
2023-12-06 3.4 49 INGGRIR.R
2022-12-30 3 46 elda sari putri
2022-12-23 3.6 48 Agus
2022-12-20 3.2 49 ETRI RAMADARLI
2022-12-29 2.8 48 Rahma Danil
2022-12-24 3.2 49 DIAN SANDI PUTRA
2022-12-14 2.6 47 ARDINAL CHANDRA
2022-12-19 3.6 51 AULIA ULHAQ
2022-12-13 2.8 48 HERU BERTA WIJAYA
2022-12-07 4.1 48 ROCKY APRINALDI
2022-12-02 2.9 48 FAJRUL ROZI
2022-12-07 3 48 Jasrul Efendi
2021-12-23 3.5 48 Delli Yusni
2021-12-21 3.2 49 Siska putri
2021-12-12 3 49 novra winardi
2021-12-14 2.7 47 YUSUF ARDY
2021-12-02 3 48 M.AL IKHSAN
2021-12-01 3.2 48 Firdaud
2024-06-24 3 49 TOS SURIANTO
2024-06-22 3.9 50 RINTO
2024-06-18 4.1 53 WITO MULYONO
2024-06-11 2.9 49 FERDINAL
2024-06-06 2.8 48 VERA MELISA
2024-06-06 3.1 48 ROKI AFRINANDO
2024-06-11 3.1 47 ARIF SYAPUTRA
2023-06-20 3.5 51 Yenti karmila
2023-06-27 3.3 47 endrizal
2023-06-15 3 48 WIILI ARFANDI
2023-06-11 3.5 48 ARIADI
2023-06-16 2.75 48 Nining furwasyih
2023-06-06 2.94 50 Sri wahyuni
2022-06-20 3.2 50 Syaridel
2022-06-17 1.9 42 PUJI RAHMADI
2022-06-05 2.8 46 RIO FERNANDO
2022-06-09 3.2 50 Dinda emilia sari
2022-06-02 3.4 47 Yulianta
2022-06-03 3.4 50 Erwandianto
2021-06-28 3.1 52 Riki darman
2021-06-29 2.7 48 DONI KURNIAWAN
2021-06-17 3.1 49 Yendra juwita
2021-06-16 2.7 47 Feri
2021-06-15 3 47 Okta s
2021-06-18 3 49 HENDRI
2021-06-10 3.6 48 Saiful
2021-06-05 3.3 49 samauza
2021-06-19 3 48 NIKE.P
2021-06-10 3.4 48 HARTO
2021-06-04 2.8 48 dodi saputra
2021-06-01 3.1 48 ARIADI
"));

        $block3 = explode("\n", trim("
JAWA GADUT 2026-06-12
PUNCAK 2026-06-12
Puncak 2026-06-17
Jawa gadut 2026-06-12
Tabing 2026-06-05
Ganting 2026-06-17
Puncak 2026-06-17
Kubang 2026-06-08
Rt 1 rw 5 2026-06-08
koto panjang 2026-06-20
ganting 2026-06-17
jawa gadut 2026-06-17
JAWA GADUT 2026-06-17
KOTO PANJANG 2026-06-06
LIMAU MANIS 2026-06-17
Limau manis 2026-06-17
KOTO PANJANG 2026-06-20
KOTO PANJANG 2026-06-20
KOTO PANJANG 2026-06-20
kubang 2026-06-08
rt 1 rw 3 2026-06-17
JAWA GADUT 2026-06-17
kubang 2026-06-08
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-17
Jawa gadut 2026-06-12
KUBANG 2026-06-08
KUBANG 2026-06-08
JAWA GADUT 2026-06-25
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-12
Limau manis 2026-06-17
jln koto panjang 2026-06-20
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-13
kubang 2026-06-08
Tabing 2026-06-05
KUBANG 2026-06-08
Koto panjang 2026-06-20
KOTO PANJANG 2026-06-20
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-17
JAWA GADUT 2026-06-17
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-12
Jawa gadut 2026-06-17
Koto panjang 2026-06-06
Kubang 2026-06-08
kubang 2026-06-08
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-12
Gantiang 2026-06-17
TABING 2026-06-05
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-17
JAWA GADUT 2026-06-12
KUBANG 2026-06-08
JAWA GADUT 2026-06-12
GANTING 2026-06-17
Tabing 2026-06-05
jawa gadut 2026-06-17
KOTO PANJANG 2026-06-06
JAWA GADUT 2026-06-12
Ganting 2026-06-17
Kubang 2026-06-08
Tabing 2026-06-05
JAWA GADUT 2026-06-12
JAWA GADUT 2026-06-12
Ganting 2026-06-17
Ganting 2026-06-17
Koto panjang 2026-06-20
Tabiang 2026-06-05
JAWA GADUT 2026-06-12
Tabiang 2026-06-05
Jawa Gadut 2026-06-05
Tabing 2026-06-05
JAWA GADUT 2026-06-12
Kubang 2026-06-08
kubang 2026-06-04
JAWA GADUT 2026-06-25
JAWA GADUT 2026-06-17
jawa gadut 2026-06-12
JAWA GADUT 2026-06-12
"));

        $block4 = explode("\n", trim("
5.5 57
5.7 60
4.8 56.8
5.3 56
4.6 58
5.3 58
5.8 58.3
6.6 61
7.6 66
7 64
6.4 62
6.8 64
8 65.2
7.4 68
7.9 66
7.4 65
8.1 69
7.6 69
7.7 69
8.9 71
8.6 71
9.2 71.5
8.4 70
8 72
8 70
9.9 73.2
9 75
10.2 79
7.3 68
10.4 77.2
12.7 87
12.2 85
11.1 88
11.6 86
12.5 91
15.5 96
14.6 104
16.2 104
13.5 102
13 92
14.8 92
15 101
13.3 100.3
14.2 97.9
15.5 102
14.5 99
15 97.2
14.4 102
16.9 116
16.4 107
14.2 102
16.2 101
16 107
12 83
11.4 82
13.6 87.5
10.2 83
12.2 87
11.2 82
11.4 83
12.8 91
16 90.8
14.4 93
13.6 94
14 98
14.3 101
12.3 98
15 101
13.8 101
16 105
15.8 107
15 100
15.5 107
16.8 106
16.1 108
15.2 106
16.8 107
15.8 107
16 110
16.2 115
17.2 106
16 107.9
18.9 108
16.5 105
"));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < 84; $i++) {
                $b1 = $block1[$i] ?? '';
                preg_match('/^\d+\s+(\d{16})\s+(.+?)\s+([PL])$/', trim($b1), $matches1);
                if (empty($matches1)) preg_match('/(\d{16})\s+(.+?)\s+([PL])$/', trim($b1), $matches1);

                $nik = $matches1[1] ?? '1234567890123456';
                $nama = trim($matches1[2] ?? 'Anak');
                $jk = ($matches1[3] ?? 'L') === 'P' ? 'female' : 'male';

                $b2 = $block2[$i] ?? '';
                preg_match('/^(\d{4}-\d{2}-\d{2})\s+([\d\.]+)\s+([\d\.]+)\s+(.+)$/', trim($b2), $matches2);
                $tglLahir = $matches2[1] ?? '2025-01-01';
                $bbLahirRaw = (float)($matches2[2] ?? 3);
                $tbLahir = (float)($matches2[3] ?? 48);
                $ortu = trim($matches2[4] ?? 'Ortu');
                
                if ($bbLahirRaw > 100) $bbLahir = $bbLahirRaw / 1000;
                else if ($bbLahirRaw > 10) $bbLahir = $bbLahirRaw / 10;
                else $bbLahir = $bbLahirRaw;

                $b3 = $block3[$i] ?? '';
                preg_match('/^(.+?)\s+(\d{4}-\d{2}-\d{2})$/', trim($b3), $matches3);
                $alamat = trim($matches3[1] ?? 'Padang');
                $tglUkur = $matches3[2] ?? '2026-06-01';

                $b4 = $block4[$i] ?? '';
                preg_match('/^([\d\.]+)\s+([\d\.]+)$/', trim($b4), $matches4);
                $berat = (float)($matches4[1] ?? 5);
                $tinggi = (float)($matches4[2] ?? 60);

                $user = User::firstOrCreate(
                    ['username' => $nik],
                    [
                        'name' => $ortu,
                        'email' => $nik . '@posyandu.test',
                        'password' => Hash::make('password123'),
                        'plain_password' => 'password123',
                        'role_id' => 2,
                        'phone' => '080000000000',
                        'address' => $alamat
                    ]
                );

                $child = Child::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $nama
                    ],
                    [
                        'gender' => $jk,
                        'birth_date' => $tglLahir,
                        'birth_place' => 'Padang',
                        'mother_name' => $ortu
                    ]
                );

                // Buat Pengukuran 0 Bulan (Data Lahir)
                $measureLahir = [
                    'measurement_date' => $tglLahir,
                    'measurement_time' => '08:00',
                    'weight' => $bbLahir,
                    'height' => $tbLahir,
                    'head_circumference' => rand(330, 370) / 10,
                    'additional_recommendation' => 'Data Lahir',
                ];
                $repository->createForChild($child, $measureLahir);

                // Buat Pengukuran Rutin
                $lingkarKepala = rand(380, 500) / 10; 
                $measureData = [
                    'measurement_date' => $tglUkur,
                    'measurement_time' => '08:00',
                    'weight' => $berat,
                    'height' => $tinggi,
                    'head_circumference' => $lingkarKepala,
                    'additional_recommendation' => 'Petugas: Bidan Desa',
                ];
                $repository->createForChild($child, $measureData);
            }
            DB::commit();
            echo "Seeder berhasil dijalankan: 84 baris diimpor.\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
