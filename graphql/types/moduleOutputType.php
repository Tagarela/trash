<?php

add_action('graphql_register_types', 'registerModuleOutputType');

function registerModuleOutputType()
{
    register_graphql_object_type('ModuleOutput', [
        'description' => __("Course Module Output", ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan Id'),
            ],
            'title' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('title'),
            ],
            'lessons' => [
                'type' => ['list_of' => 'LessonOutput'],
                'description' => __('course Lessons'),
            ]
        ]
    ]);
}