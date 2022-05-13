<?php

/*** Register GraphQL TYPES ***/
add_action('graphql_register_types', 'nursing_practice_question_type');
add_action('graphql_register_types', 'user_study_plan_type');

/**
 * Register nursing practice question response type
 */
function nursing_practice_question_type()
{
    register_graphql_object_type('AcfApaReferenceGroupType', [
        'description' => __("AcfApaReferenceGroupType", ''),
        'fields' => [
            'apa_reference' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Apa Reference'),
            ]
        ]
    ]);

    register_graphql_object_type('AcfAnswerType', [
        'description' => __("AcfAnswer", ''),
        'fields' => [
            'answer' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Acf Answer'),
            ]
        ]
    ]);
    register_graphql_object_type('AcfTxtType', [
        'description' => __("AcfAnswer", ''),
        'fields' => [
            'txt' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Acf Answer Rationale Answer'),
            ]
        ]
    ]);

    register_graphql_object_type('AcfAnswerRationaleType', [
        'description' => __("AcfAnswer", ''),
        'fields' => [
            'answer' => [
                'type' => 'AcfTxtType',
                'description' => __('Acf Answer Rationale Answer'),
            ],
            'correct_status' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('Correct status'),
            ],
            'rationale' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('rationale'),
            ],
        ]
    ]);

    register_graphql_object_type('AcfUserFeedbackType', [
        'description' => __("AcfUserFeedbackType", ''),
        'fields' => [
            'user_feedback' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('user feedback'),
            ]
        ]
    ]);

    register_graphql_object_type('KeyValueObject', [
        'description' => __("Key Value Object", ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('id'),
            ],
            'value' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('value'),
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
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('question'),
            ],
            'correct_answer' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('correct answer Id'),
            ],
            'answer_type' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('answer type'),
            ],
            'rationale' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('rationale'),
            ],
            'rationale_image' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('rationale image'),
            ],
            'rationale_image_reference' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
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
                'type' => GraphQL\Type\Definition\Type::BOOLEAN,
                'description' => __('nrsng reference group')
            ],
            'unique_question_id' => [
                'type' => 'String',
                'description' => __('unique question id')
            ],
            'alternate_format_picture' => [
                'type' => GraphQL\Type\Definition\Type::BOOLEAN,
                'description' => __('alternate format picture')
            ],
            'correct_answers' => [
                'type' => ['list_of' => 'AcfTxtType'],
                'description' => __('answer desc references'),
            ],
            'correct_answers_count' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('correct answers'),
                'resolve' => function( $context ) {
                    return $context['correct answers'];
                }
            ],
            'total_answers' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('total answers'),
                'resolve' => function( $context ) {
                    return $context['total answers'];
                }
            ],
            'percentright' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('percent right')
            ],
            'positive_feedback' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('positive feedback'),
                'resolve' => function( $context ) {
                    return $context['positive feedback'];
                }
            ],
           'negative_feedback' => [
               'type' => GraphQL\Type\Definition\Type::STRING,
               'description' => __('negative feedback'),
               'resolve' => function( $context ) {
                   return $context['negative feedback'];
               }
           ],
            'quiz_comment' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('quiz comment'),
                'resolve' => function( $context ) {
                    return $context['quiz comment'];
                }
            ],
            'hide_from_simclex' => [
                'type' => 'Boolean',
                'description' => __('hide from simclex'),
            ],
            'answer_desc' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('answer description'),
            ],
            'answers' => [
                'type' => ['list_of' => 'AcfAnswerType'],
                'description' => __('answers'),
            ],
            'answer_rationales' => [
                'type' => ['list_of' => 'AcfAnswerRationaleType'],
                'description' => __('answer Rationale Type'),
            ],
            'type' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('ACF type'),
            ],
            'user_feedback_group' => [
                'type' => ['list_of' => 'AcfUserFeedbackType'],
                'description' => __('user feedback group'),
                'resolve' => function( $context ) {
                    if($context['user_feedback_group'] === false) {
                        return [];
                    }
                    return $context['user_feedback_group'];
                }
            ],
            'answer_desc_references' => [
                'type' => ['list_of' => 'AcfTxtType'],
                'description' => __('answer desc references'),
                'resolve' => function( $context ) {
                    if($context['answer_desc_references'] === false) {
                        return [];
                    }
                    return $context['answer_desc_references'];
                }
            ],
            'keywords' => [
                'type' => ['list_of' => GraphQL\Type\Definition\Type::STRING],
                'description' => __('keywords'),
                'resolve' => function( $context ) {
                    if(!is_array($context['keywords'])) {
                        return [$context['keywords']];
                    }
                    return $context['keywords'];
                }
            ]
        ]
    ]);

    register_graphql_object_type('NursingPracticeQuestion', [
        'description' => __("Nursing Practice Question", ''),
        'fields' => [
            'id' => [
                'type' => GraphQL\Type\Definition\Type::INT,
                'description' => __('id'),
            ],
            'post_modified' => [
                'type' => GraphQL\Type\Definition\Type::STRING,
                'description' => __('post_modified'),
            ],
            'acf' => [
                'type' => 'AcfType',
                'description' => __('acf'),
            ],
            'nclex_category' => [
                'type' => 'KeyValueObject',
                'description' => __('nclex category'),
                'resolve' => function( $context ) {
                    if(empty($context['nclex-category'])) {
                        return null;
                    }
                    $resObject = new stdClass();
                    foreach($context['nclex-category'] as $key=>$value) {
                        $resObject->id = $key;
                        $resObject->value = $value;
                    }
                    return $resObject;
                }
            ],
            'nursing_category' => [
                'type' => 'KeyValueObject',
                'description' => __('nursing category'),
                'resolve' => function( $context ) {
                    if(empty($context['nursing-category'])) {
                        return null;
                    }
                    $resObject = new stdClass();
                    foreach($context['nursing-category'] as $key=>$value) {
                        $resObject->id = $key;
                        $resObject->value = $value;
                    }
                    return $resObject;
                }
            ],
            'hesi_category' => [
                'type' => 'KeyValueObject',
                'description' => __('hesi category'),
                'resolve' => function( $context ) {
                    if(empty($context['hesi-category'])) {
                        return null;
                    }
                    $resObject = new stdClass();
                    foreach($context['hesi-category'] as $key=>$value) {
                        $resObject->id = $key;
                        $resObject->value = $value;
                    }
                    return $resObject;
                }
            ],
            'teas_category' => [
                'type' => 'KeyValueObject',
                'description' => __('hesi category'),
                'resolve' => function( $context ) {
                    if(empty($context['teas-category'])) {
                        return null;
                    }
                    $resObject = new stdClass();
                    foreach($context['teas-category'] as $key=>$value) {
                        $resObject->id = $key;
                        $resObject->value = $value;
                    }
                    return $resObject;
                }
            ]
        ],
    ]);
}

function user_study_plan_type() {
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