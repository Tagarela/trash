<?php
add_action('graphql_register_types', 'registerUserStudyPlanType');

function registerUserStudyPlanType() {
    register_graphql_object_type('UserStudyPlan', [
        'description' => __("User Study Plan", ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan Id'),
            ],
            'date' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan date'),
            ],
            'title' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan title'),
            ],
            'mastered' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Study Plan mastered'),
            ]
        ]
    ]);
}