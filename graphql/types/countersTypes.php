<?php
add_action('graphql_register_types', 'registerCounterType');

function registerCounterType()
{
    register_graphql_object_type('CountersOutput', [
        'description' => __("counters Output", ''),
        'fields' => [
            'bookCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course book count'),
            ],
            'caseStudyCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Questions count'),
            ],
            'carePlanCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Questions count'),
            ],
            'cheatSheetCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Cheat Sheet count'),
            ],
            'imageCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Lessons count'),
            ],
            'lessonsCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Lessons count'),
            ],
            'mnemonicCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Mnemonic count'),
            ],
            'picmonicCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Mnemonic count'),
            ],
            'questionCount' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('course Questions count'),
            ],
        ]
    ]);
}
