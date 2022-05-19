<?php
add_action('graphql_register_types', 'registerLessonOutputType');

function registerLessonOutputType()
{
    /*** Lesson Question Output ***/
    register_graphql_object_type('LessonOutput', [
        'description' => __("Course Module Lesson Output", ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan Id'),
            ],
            'title' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('title'),
            ],
            'completedPercent' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('title'),
            ],
            'counters' => [
                'type' => 'CountersOutput',
                'description' => __('title'),
            ],
            'questions' => [
                'type' => ['list_of' => GraphQL\Type\Definition\Type::STRING],
                'description' => __('questions'),
            ]
        ]
    ]);
}
