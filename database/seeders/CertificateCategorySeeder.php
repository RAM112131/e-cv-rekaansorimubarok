<?php

namespace Database\Seeders;

use App\Models\CertificateCategory;
use Illuminate\Database\Seeder;

class CertificateCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Web Development',
            'Mobile Development',
            'Cloud & DevOps',
            'Cyber Security',
            'Data & AI',
            'UI/UX Design',
            'Sertifikat Keahlian',
            'Kompetisi',
            'Training & Course',
            'Honors & Penghargaan',
        ];

        foreach ($categories as $i => $name) {
            CertificateCategory::firstOrCreate(
                ['name' => $name],
                ['order' => $i + 1]
            );
        }
    }
}
