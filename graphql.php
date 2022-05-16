<?php
include_once 'graphql/types.php';
include_once 'graphql/GraphQlControllerHelper.php';

// Return mastered lessons based on user ID
add_action('graphql_register_types', function () {
    register_graphql_field('RootQuery', 'getUserMasteredLessons', [
        'type' => ['list_of' => 'Number'],
        'description' => __('Get mastered lessons for a specific user ID', 'wp-graphql'),
        'args' => [
            'id' => [
                'type' => 'ID',
                'description' => __('The ID of the user', 'wp-graphql')
            ]
        ],
        'resolve' => function ($root, $args, $context, $info) {
            return get_user_meta($args['id'], 'completed_lessons', true);
        }
    ]);
    /*** Get Study Plans ***/
    register_graphql_field('RootQuery', 'getStudyPlanList', [
        'type' =>  ['list_of' => 'UserStudyPlan'],
        'description' => __('Get Study Plan List ', 'wp-graphql'),
        'args' => [],
        'resolve' => function ($root, $args, $context, $info) {
            $user = wp_get_current_user();
            if ($user->ID == 0) {
                throw new \GraphQL\Error\UserError('auth errror');
            }
            return GraphQlControllerHelper::getUserStudyPlan($user->ID);
        }
    ]);

    /*** GET COURSES ***/
    register_graphql_field('RootQuery', 'getCourseList', [
        'type' =>  ['list_of' => 'CourseOutput'],
        'description' => __('Get Course List ', 'wp-graphql'),
        'args' => [
            'termId' => [
                'type' => 'ID',
                'description' => __('The ID of the user', 'wp-graphql')
            ]
        ],
        'resolve' => function ($root, $args, $context, $info) {
            $user = wp_get_current_user();
            if ($user->ID == 0) {
                throw new \GraphQL\Error\UserError('auth errror');
            }
            $graphQlControllerHelper = new GraphQlControllerHelper();
            return $graphQlControllerHelper->getCourseList($args['termId']);
        }
    ]);

    /*** Get Nursing Practice Question ***/
    register_graphql_field('RootQuery', 'getNursingPracticeQuestionList', [
        'type' => ['list_of' => 'NursingPracticeQuestion'],
        'description' => __('Get Nursing Practice Question', 'wp-graphql'),
        'args' => [
            'id' => [
                'type' => \GraphQL\Type\Definition\Type::INT,
                'description' => __('limit Of records', 'wp-graphql')
            ],
            'limit' => [
                'type' => \GraphQL\Type\Definition\Type::INT,
                'description' => __('limit Of records', 'wp-graphql')
            ]
        ],
        'resolve' => function ($root, $args, $context, $info) {
            $user = wp_get_current_user();
            if ($user->ID == 0) {
                throw new \GraphQL\Error\UserError('auth errror');
            }
            return GraphQlControllerHelper::getNursingPracticeQuestion($args);
        }
    ]);
});

// Add mastered lesson
register_graphql_mutation('addUserMasteredLesson', [
    'inputFields' => [
        'userID' => [
            'type' => 'Number',
            'description' => __('ID of the user', 'wp-graphql'),
        ],
        'lessonID' => [
            'type' => 'Number',
            'description' => __('ID of the lesson to be added', 'wp-graphql'),
        ]
    ],
    'outputFields' => [
        'updatedLessons' => [
            'type' => ['list_of' => 'Number'],
            'description' => __('Original array of lesson IDs', 'wp-graphql'),
            'resolve' => function ($payload, $args, $context, $info) {
                return isset($payload['updatedLessons']) ? $payload['updatedLessons'] : null;
            }
        ],
    ],
    'mutateAndGetPayload' => function ($input, $context, $info) {
        $lessons = get_user_meta($input['userID'], 'completed_lessons', true);
        $lessons[] = $input['lessonID'];
        update_user_meta($input['userID'], 'completed_lessons', $lessons);

        return [
            'updatedLessons' => $lessons,
        ];
    }
]);

// Remove mastered lesson
register_graphql_mutation('removeUserMasteredLesson', [
    'inputFields' => [
        'userID' => [
            'type' => 'Number',
            'description' => __('ID of the user', 'wp-graphql'),
        ],
        'lessonID' => [
            'type' => 'Number',
            'description' => __('ID of the lesson to be added', 'wp-graphql'),
        ]
    ],
    'outputFields' => [
        'updatedLessons' => [
            'type' => ['list_of' => 'Number'],
            'description' => __('Original array of lesson IDs', 'wp-graphql'),
            'resolve' => function ($payload, $args, $context, $info) {
                return isset($payload['updatedLessons']) ? $payload['updatedLessons'] : null;
            }
        ],
    ],
    'mutateAndGetPayload' => function ($input, $context, $info) {
        $lessons = get_user_meta($input['userID'], 'completed_lessons', true);
        $index = array_search($input['lessonID'], $lessons);
        unset($lessons[$index]);

        update_user_meta($input['userID'], 'completed_lessons', $lessons);

        return [
            'updatedLessons' => $lessons,
        ];
    }
]);