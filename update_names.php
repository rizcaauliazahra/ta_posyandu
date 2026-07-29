<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ayahNames = ['Budi Santoso', 'Andi Pratama', 'Joko Widodo', 'Rizky Febian', 'Dimas Anggara', 'Bayu Skak', 'Hendra Gunawan', 'Agus Harimurti', 'Wahyu Hidayat', 'Ahmad Dhani', 'Irfan Hakim', 'Reza Rahadian', 'Vino Bastian', 'Rio Dewanto', 'Chicco Jerikho', 'Tora Sudiro', 'Lukman Sardi', 'Deddy Corbuzier', 'Raffi Ahmad', 'Baim Wong', 'Dude Harlino', 'Teuku Wisnu', 'Ammar Zoni', 'Stefan William', 'Verrel Bramasta', 'Aliando Syarief', 'Al Ghazali', 'El Rumi', 'Dul Jaelani', 'Iqbaal Ramadhan', 'Adipati Dolken', 'Jefri Nichol', 'Angga Yunanda', 'Arya Saloka', 'Evan Sanders', 'Surya Saputra', 'Oka Antara', 'Arifin Putra', 'Ario Bayu', 'Joe Taslim', 'Iko Uwais', 'Yayan Ruhian', 'Vidi Aldiano', 'Afgan Syahreza', 'Judika', 'Ariel Noah', 'Giring Ganesha', 'Pasha Ungu', 'Armand Maulana', 'David Naif', 'Duta Sheila On 7', 'Eross Candra', 'Ian Kasela'];
$ibuNames = ['Siti Nurhaliza', 'Ayu Ting Ting', 'Putri Titian', 'Dewi Perssik', 'Sari Nila', 'Lestari', 'Sri Wahyuni', 'Nita Thalia', 'Rini Wulandari', 'Wati', 'Dian Sastrowardoyo', 'Dian Sastro', 'Luna Maya', 'Cut Tari', 'Sophia Latjuba', 'Wulan Guritno', 'Tamara Bleszynski', 'Desy Ratnasari', 'Nike Ardilla', 'Mulan Jameela', 'Maia Estianty', 'Krisdayanti', 'Yuni Shara', 'Rosa', 'Bunga Citra Lestari', 'Raisa', 'Isyana Sarasvati', 'Agnez Mo', 'Maudy Ayunda', 'Chelsea Islan', 'Pevita Pearce', 'Tara Basro', 'Dian Pelangi', 'Zaskia Sungkar', 'Shireen Sungkar', 'Nia Ramadhani', 'Jessica Iskandar', 'Gisella Anastasia', 'Aurel Hermansyah', 'Ashanty', 'Syahrini', 'Nagita Slavina', 'Paula Verhoeven', 'Sarwendah', 'Anya Geraldine', 'Raline Shah', 'Ayu Dewi', 'Melaney Ricardo', 'Luna Maya', 'Cinta Laura', 'Marion Jola', 'Tiara Andini', 'Lyodra', 'Ziva Magnolya'];

$children = App\Models\Child::all();
foreach ($children as $index => $child) {
    $ayah = $ayahNames[array_rand($ayahNames)];
    $ibu = $ibuNames[array_rand($ibuNames)];
    
    $child->father_name = $ayah;
    $child->mother_name = $ibu;
    $child->save();
    
    if ($child->user) {
        $user = $child->user;
        $user->name = $ibu; // As mother is often the main parent
        
        $emailPrefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ibu));
        // Ensure unique email by appending index
        $user->email = $emailPrefix . '@gmail.com';
        
        // Cek jika email sudah ada (untuk avoid collision walau kecil kemungkinannya)
        $suffix = 1;
        while (App\Models\User::where('email', $user->email)->where('id', '!=', $user->id)->exists()) {
            $user->email = $emailPrefix . $suffix . '@gmail.com';
            $suffix++;
        }
        
        $user->save();
    }
}
echo 'Update 84 data selesai!';
