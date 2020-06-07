<?php

namespace App\Models;

class Constants
{
    public const UOM = [
        array('id' => '00000001-0001-43ad-a18c-000000000001', 'slug' => 'batang', 'name' => 'Batang'),
        array('id' => '00000001-0001-43ad-a18c-000000000002', 'slug' => 'buah', 'name' => 'Buah'),
        array('id' => '00000001-0001-43ad-a18c-000000000003', 'slug' => 'centimeter-kubik', 'name' => 'Centimeter Kubik'),
        array('id' => '00000001-0001-43ad-a18c-000000000004', 'slug' => 'ekor', 'name' => 'Ekor'),
        array('id' => '00000001-0001-43ad-a18c-000000000005', 'slug' => 'gelas', 'name' => 'Gelas'),
        array('id' => '00000001-0001-43ad-a18c-000000000006', 'slug' => 'kilogram', 'name' => 'Kilogram'),
        array('id' => '00000001-0001-43ad-a18c-000000000007', 'slug' => 'lainnya', 'name' => 'Lainnya'),
        array('id' => '00000001-0001-43ad-a18c-000000000008', 'slug' => 'miligram', 'name' => 'Miligram'),
        array('id' => '00000001-0001-43ad-a18c-000000000009', 'slug' => 'pack', 'name' => 'Pack'),
        array('id' => '00000001-0001-43ad-a18c-000000000010', 'slug' => 'porsi', 'name' => 'Porsi'),
        array('id' => '00000001-0001-43ad-a18c-000000000011', 'slug' => 'unit', 'name' => 'Unit'),
        array('id' => '00000001-0001-43ad-a18c-000000000012', 'slug' => 'boks', 'name' => 'Boks'),
        array('id' => '00000001-0001-43ad-a18c-000000000013', 'slug' => 'butir', 'name' => 'Butir'),
        array('id' => '00000001-0001-43ad-a18c-000000000014', 'slug' => 'cup', 'name' => 'Cup'),
        array('id' => '00000001-0001-43ad-a18c-000000000015', 'slug' => 'gram', 'name' => 'Gram'),
        array('id' => '00000001-0001-43ad-a18c-000000000016', 'slug' => 'kaleng', 'name' => 'Kaleng'),
        array('id' => '00000001-0001-43ad-a18c-000000000017', 'slug' => 'liter', 'name' => 'Liter'),
        array('id' => '00000001-0001-43ad-a18c-000000000018', 'slug' => 'mililiter', 'name' => 'Mililiter'),
        array('id' => '00000001-0001-43ad-a18c-000000000019', 'slug' => 'paket', 'name' => 'Paket'),
        array('id' => '00000001-0001-43ad-a18c-000000000020', 'slug' => 'roll', 'name' => 'Roll'),
        array('id' => '00000001-0001-43ad-a18c-000000000021', 'slug' => 'yard', 'name' => 'Yard'),
        array('id' => '00000001-0001-43ad-a18c-000000000022', 'slug' => 'botol', 'name' => 'Botol'),
        array('id' => '00000001-0001-43ad-a18c-000000000023', 'slug' => 'centimeter', 'name' => 'Centimeter'),
        array('id' => '00000001-0001-43ad-a18c-000000000024', 'slug' => 'galon', 'name' => 'Galon'),
        array('id' => '00000001-0001-43ad-a18c-000000000025', 'slug' => 'karton', 'name' => 'Karton'),
        array('id' => '00000001-0001-43ad-a18c-000000000026', 'slug' => 'kotak', 'name' => 'Kotak'),
        array('id' => '00000001-0001-43ad-a18c-000000000027', 'slug' => 'meter', 'name' => 'Meter'),
        array('id' => '00000001-0001-43ad-a18c-000000000028', 'slug' => 'ounce', 'name' => 'Ounce'),
        array('id' => '00000001-0001-43ad-a18c-000000000029', 'slug' => 'pieces', 'name' => 'Pieces'),
        array('id' => '00000001-0001-43ad-a18c-000000000030', 'slug' => 'sachet', 'name' => 'Sachet'),
    ];

    public const DONATION_CATEGORIES = [
        array('id' => '00000000-0001-43ad-a18c-000000000001', 'slug' => 'medis', 'name' => 'Medis'),
        array('id' => '00000000-0002-43ad-a18c-000000000002', 'slug' => 'non-medis', 'name' => 'Non Medis'),
        array('id' => '00000000-0003-43ad-a18c-000000000003', 'slug' => 'logistik', 'name' => 'Logistik'),
        array('id' => '00000000-0003-43ad-a18c-000000000004', 'slug' => 'tunai', 'name' => 'Tunai'),
    ];

    public const INVESTOR_STATUS = [
        array('id' => '11000000-0001-13ab-b18d-000000000001', 'slug' => 'pending', 'name' => 'Pending'),
        array('id' => '11000000-0002-13ab-b18d-000000000002', 'slug' => 'verified', 'name' => 'Verified'),
    ];

    public const INVESTOR_CATEGORIES = [
        array('id' => '10000000-0001-44bd-a26c-000000000001', 'slug' => 'personal', 'name' => 'Personal'),
        array('id' => '10000000-0002-44bd-a26c-000000000002', 'slug' => 'perusahaan', 'name' => 'Perusahaan/Organisasi'),
    ];

    public const INVESTOR_RATING = [
        array('id' => '20000000-0001-44fd-a26a-000000000000', 'slug' => 'non', 'name' => ''),
        array('id' => '20000000-0001-44fd-a26a-000000000001', 'slug' => 'angle', 'name' => 'Angel Investor', 'description' => ''),
        array('id' => '20000000-0002-44fd-a26a-000000000002', 'slug' => 'middle', 'name' => 'Middle Investor', 'description' => ''),
        array('id' => '20000000-0003-44fd-a26a-000000000003', 'slug' => 'senior', 'name' => 'Senior investor', 'description' => ''),
    ];
}
