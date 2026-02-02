<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Incident Form Fields
         */
        $incidentForm = Form::where('name', 'Incident')->first();
        if ($incidentForm) {
            $incidentForm->fields()->createMany([
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255',
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'order' => 1
                ],
                [
                    'name' => 'incident_type',
                    'label' => 'Incident Type',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Data Issue' => 'Data Issue',
                        'Access Issue' => 'Access Issue',
                        'Service Disruption' => 'Service Disruption'
                    ],
                    'order' => 2
                ],
                [
                    'name' => 'impact',
                    'label' => 'Impact',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High'
                    ],
                    'order' => 3
                ],
                [
                    'name' => 'urgency',
                    'label' => 'Urgency',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High'
                    ],
                    'order' => 4
                ],
                [
                    'name' => 'incident_date',
                    'label' => 'Incident Date',
                    'type' => 'date',
                    'is_required' => false,
                    'validation_rules' => 'date|date_format:Y-m-d|before_or_equal:today',
                    'order' => 5
                ],
                [
                    'name' => 'still_happening',
                    'label' => 'Is it still happening?',
                    'type' => 'radio',
                    'is_required' => true,
                    'options' => ['Yes' => 'Yes', 'No' => 'No'],
                    'order' => 6
                ],
                [
                    'name' => 'attachments',
                    'label' => 'Attachments',
                    'type' => 'file_multiple',
                    'is_required' => false,
                    'validation_rules' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
                    'order' => 7
                ]
            ]);
        }

        /**
         * Change Request Form Fields
         */
        $changeRequest = Form::where('name', 'Change Request')->first();
        if ($changeRequest) {
            $changeRequest->fields()->createMany([
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255',
                    'order' => 1
                ],
                [
                    'name' => 'change_type',
                    'label' => 'Change Type',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'New Feature' => 'New Feature',
                        'Enhancement' => 'Enhancement',
                        'Bug Fix' => 'Bug Fix',
                        'Configuration Change' => 'Configuration Change',
                        'UI Update' => 'UI Update',
                        'Integration' => 'Integration',
                        'Security' => 'Security',
                        'Other' => 'Other',
                    ],
                    'order' => 2
                ],
                [
                    'name' => 'category',
                    'label' => 'Change Category',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Application' => 'Application',
                        'Database' => 'Database',
                        'Network' => 'Network',
                        'Infrastructure' => 'Infrastructure',
                        'Access Control' => 'Access Control',
                    ],
                    'order' => 3
                ],
                [
                    'name' => 'urgency',
                    'label' => 'Urgency',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'],
                    'order' => 4
                ],
                [
                    'name' => 'risk_level',
                    'label' => 'Risk Level',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => ['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'],
                    'order' => 5
                ],
                [
                    'name' => 'current_situation',
                    'label' => 'Current Situation',
                    'type' => 'textarea',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255',
                    'order' => 6
                ],
                [
                    'name' => 'acceptance_criteria',
                    'label' => 'Accept Criteria',
                    'type' => 'textarea',
                    'is_required' => false,
                    'validation_rules' => 'min:3|max:255',
                    'order' => 7
                ],
                [
                    'name' => 'attachments',
                    'label' => 'Attachments',
                    'type' => 'file_multiple',
                    'is_required' => false,
                    'validation_rules' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
                    'order' => 8
                ]
            ]);
        }

        /**
         * Training Form Fields
         */
        $training = Form::where('name', 'Training')->first();
        if ($training) {
            $training->fields()->createMany([
                [
                    'name' => 'title',
                    'label' => 'Training Topic',
                    'type' => 'text',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255',
                    'order' => 1
                ],
                [
                    'name' => 'type',
                    'label' => 'Training Type',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'System Training' => 'System Training',
                        'Refresher' => 'Refresher',
                        'New Feature Training' => 'New Feature Training',
                        'Admin Training' => 'Admin Training',
                        'User Training' => 'User Training',
                        'Other' => 'Other',
                    ],
                    'order' => 2
                ],
                [
                    'name' => 'mode',
                    'label' => 'Training Mode',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Onsite' => 'Onsite',
                        'Online' => 'Online',
                        'Hybrid' => 'Hybrid',
                    ],
                    'order' => 3
                ],
                [
                    'name' => 'audience',
                    'label' => 'Target Audience',
                    'type' => 'select_multiple',
                    'is_required' => true,
                    'options' => [
                        'End Users' => 'End Users',
                        'Admins' => 'Admins',
                        'Managers' => 'Managers',
                        'Technicians' => 'Technicians',
                    ],
                    'order' => 4
                ],
                [
                    'name' => 'number_of_trainees',
                    'label' => 'Number of Trainers',
                    'type' => 'number',
                    'is_required' => true,
                    'validation_rules' => 'numeric|min:1',
                    'order' => 5
                ],
                [
                    'name' => 'training_level',
                    'label' => 'Training Level',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Beginner' => 'Beginner',
                        'Intermediate' => 'Intermediate',
                        'Advanced' => 'Advanced',
                    ],
                    'order' => 6
                ],
                [
                    'name' => 'key_areas_to_cover',
                    'label' => 'Key Areas To Cover',
                    'type' => 'textarea',
                    'is_required' => true,
                    'validation_rules' => 'min:3|max:255',
                    'order' => 7
                ],
                [
                    'name' => 'materials_needed',
                    'label' => 'Materials Needed',
                    'type' => 'select_multiple',
                    'is_required' => true,
                    'options' => [
                        'Slides' => 'Slides',
                        'Manual' => 'Manual',
                        'Demo System' => 'Demo System',
                        'Videos' => 'Videos',
                    ],
                    'order' => 8
                ],
                [
                    'name' => 'training_date',
                    'label' => 'Preferred Training Date',
                    'type' => 'date',
                    'is_required' => true,
                    'validation_rules' => 'date|after:today',
                    'order' => 9
                ],
                [
                    'name' => 'slot',
                    'label' => 'Preferred Training Slot',
                    'type' => 'select',
                    'is_required' => true,
                    'options' => [
                        'Morning' => 'Morning',
                        'Afternoon' => 'Afternoon',
                        'Evening' => 'Evening',
                    ],
                    'order' => 10
                ],
                [
                    'name' => 'duration',
                    'label' => 'Estimated Duration (days)',
                    'type' => 'number',
                    'is_required' => true,
                    'validation_rules' => 'numeric|min:1',
                    'order' => 11
                ],
                [
                    'name' => 'attachments',
                    'label' => 'Attachments',
                    'type' => 'file_multiple',
                    'is_required' => false,
                    'validation_rules' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
                    'order' => 12
                ]
            ]);
        }
    }
}
