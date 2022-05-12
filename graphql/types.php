<?php

/*** Register GraphQL TYPES ***/
add_action('graphql_register_types', 'nursing_practice_question_type');

/**
 * Register nursing practice question response type
 */
function nursing_practice_question_type()
{
    register_graphql_object_type('AcfApaReferenceGroupType', [
        'description' => __("AcfApaReferenceGroupType", ''),
        'fields' => [
            'apa_reference' => [
                'type' => 'String',
                'description' => __('Apa Reference'),
            ]
        ]
    ]);

    /**
     * Register Type AcfType
     */
    register_graphql_object_type('AcfType', [
        'description' => __("ACF", ''),
        'fields' => [
            'question' => [
                'type' => 'String',
                'description' => __('question'),
            ],
            'correct_answer' => [
                'type' => 'Integer',
                'description' => __('correct answer Id'),
            ],
            'answer_type' => [
                'type' => 'String',
                'description' => __('answer type'),
            ],
            'rationale' => [
                'type' => 'String',
                'description' => __('rationale'),
            ],
            'rationale_image' => [
                'type' => 'String',
                'description' => __('rationale image'),
            ],
            'rationale_image_reference' => [
                'type' => 'String',
                'description' => __('rationale image reference'),
            ],
            'apa_reference_group' => [
                'type' => ['list_of' => 'AcfApaReferenceGroupType'],
                'description' => __('rationale image reference', 'replace-me'),
                'resolve' => function( $context ) {
                    if($context['apa_reference_group'] === false){
                        return [];
                    }
                    return $context['apa_reference_group'];
                }
            ],
            'nrsng_reference_group' => [
                'type' => 'Boolean',
                'description' => __('nrsng reference group'),
            ],
            'unique_question_id' => [
                'type' => 'Integer',
                'description' => __('unique question id'),
            ],
            'alternate_format_picture' => [
                'type' => 'Boolean',
                'description' => __('nrsng reference group'),
            ],
            'type' => [
                'type' => 'String',
                'description' => __('ACF type'),
            ]
        ]
    ]);

    register_graphql_object_type('NursingPracticeQuestion', [
        'description' => __("Nursing Practice Question", ''),
        'fields' => [
            'id' => [
                'type' => 'Integer',
                'description' => __('id'),
            ],
            'post_modified' => [
                'type' => 'String',
                'description' => __('post_modified'),
            ],
            'acf' => [
                'type' => 'AcfType',
                'description' => __('acf'),
            ]
        ],
    ]);
}