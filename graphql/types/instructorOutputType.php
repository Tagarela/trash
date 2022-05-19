<?php
add_action('graphql_register_types', 'registerInstructorOutputType');

function registerInstructorOutputType()
{
    register_graphql_object_type('InstructorOutput', [
        'description' => __('Instructor Output', ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Instructor Id'),
            ],
            'thumbnail' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('thumbnail'),
            ],
            'name' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('name'),
            ],
            'degree' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('degree'),
            ],
            'bio' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('bio'),
            ],
            'certifications' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('certifications'),
            ],
            'license' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('license'),
            ]
        ]
    ]);
}