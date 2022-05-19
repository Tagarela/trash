<?php

add_action('graphql_register_types', 'registerCourseOutputType');

function registerCourseOutputType() {
    register_graphql_object_type('CourseDetailsOutput', [
        'description' => __('Course Data Output', ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Course Id'),
            ],
            'title' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('title'),
            ],
            'post_modified' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('title'),
            ],
            'counters' => [
                'type' => 'CountersOutput',
                'description' => __('title'),
            ],
            'instructors' => [
                'type' => ['list_of' => 'InstructorOutput'],
                'description' => __('instructors'),
            ],
            'modules' => [
                'type' => ['list_of' => 'ModuleOutput'],
                'description' => __('modules'),
            ]
        ]
    ]);

    register_graphql_object_type('CourseOutput', [
        'description' => __('Course Output', ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Course Output Id'),
            ],
            'title' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('title'),
            ],
            'featuredImage' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('image '),
            ],
            'description' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('description'),
            ],
            'courseLessons' => [
                'type' => ['list_of' => GraphQL\Type\Definition\Type::INT],
                'description' => __('course Lessons'),
            ],
            'publishedLessons' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Lessons'),
            ],
            'modules' => [
                'type' => ['list_of' => 'ModuleOutput'],
                'description' => __('course Modules'),
            ]
        ]
    ]);
}