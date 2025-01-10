<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormField;

class FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            ['name' => 'company', 'label' => 'Company', 'type' => 'text', 'placeholder' => 'Enter company name'], 
            ['name' => 'jobTitle', 'label' => 'Job Title', 'type' => 'text', 'placeholder' => 'Enter job title'], 
            ['name' => 'topicsOfInterest', 'label' => 'Topics of Interest', 'type' => 'textarea', 'placeholder' => 'Enter topics of interest'], 
            ['name' => 'linkedIn', 'label' => 'LinkedIn Profile', 'type' => 'url', 'placeholder' => 'Enter LinkedIn profile URL'], 
            ['name' => 'website', 'label' => 'Website', 'type' => 'url', 'placeholder' => 'Enter website URL'], 
            ['name' => 'gender', 'label' => 'Gender', 'type' => 'select', 'options' => json_encode(['Male', 'Female', 'Non-Binary'])], 
            ['name' => 'preferredDate', 'label' => 'Preferred Date', 'type' => 'date', 'placeholder' => 'Select preferred date'], 
            ['name' => 'preferredTime', 'label' => 'Preferred Time', 'type' => 'time', 'placeholder' => 'Select preferred time'], 
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number'], 
            ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'placeholder' => 'Enter address'],
        ];
        foreach ($fields as $field) {
            FormField::create($field);
        }
    }
}
