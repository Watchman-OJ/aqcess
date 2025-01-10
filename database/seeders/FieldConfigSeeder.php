<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FieldConfig;

class FieldConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            ['category' => 'meeting', 'fields' => json_encode([
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'placeholder' => 'Enter name'],
                ['name' => 'sex', 'label' => 'Sex', 'type' => 'text', 'placeholder' => 'Enter sex'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email'],
                ['name' => 'attendeeType', 'label' => 'Attendee Type', 'type' => 'select', 'options' => json_encode(['Speaker', 'Participant', 'Organizer'])]
            ])],
            ['category' => 'party', 'fields' => json_encode([
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'placeholder' => 'Enter name'],
                ['name' => 'sex', 'label' => 'Sex', 'type' => 'text', 'placeholder' => 'Enter sex'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email'],
                ['name' => 'plusOne', 'label' => 'Plus One or More', 'type' => 'number', 'placeholder' => 'Enter number of plus ones']
            ])],
            ['category' => 'appointment', 'fields' => json_encode([
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'placeholder' => 'Enter name'],
                ['name' => 'sex', 'label' => 'Sex', 'type' => 'text', 'placeholder' => 'Enter sex'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email'],
                ['name' => 'reasonForAppointment', 'label' => 'Reason for Appointment', 'type' => 'textarea', 'placeholder' => 'Enter reason for appointment']
            ])],
            ['category' => 'reservation', 'fields' => json_encode([
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'placeholder' => 'Enter name'],
                ['name' => 'sex', 'label' => 'Sex', 'type' => 'text', 'placeholder' => 'Enter sex'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'placeholder' => 'Enter phone number'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email'],
                ['name' => 'numberOfGuests', 'label' => 'Number of Guests', 'type' => 'number', 'placeholder' => 'Enter number of guests']
            ])]
        ];

        foreach ($configs as $config) {
            FieldConfig::create($config);
        }
    }
}

