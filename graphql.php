<?php
include_once 'graphql/types.php';
include_once 'graphql/GraphQlControllerHelper.php';

// Return mastered lessons based on user ID
add_action( 'graphql_register_types', function() {
	register_graphql_field( 'RootQuery', 'getUserMasteredLessons', [
		'type' => ['list_of' => 'Number'],
		'description' => __( 'Get mastered lessons for a specific user ID', 'wp-graphql' ),
		'args' => [
			'id' => [
				'type' => 'ID',
				'description' => __('The ID of the user', 'wp-graphql')
			]
			],
		'resolve' => function($root, $args, $context, $info) {
			return get_user_meta($args['id'], 'completed_lessons', true);
		}
	]);

    register_graphql_field( 'RootQuery', 'getNursingPracticeQuestion', [
        'type' => ['list_of' => 'NursingPracticeQuestion'],
        'description' => __( 'Get Nursing Practice Question', 'wp-graphql' ),
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
        'resolve' => function($root, $args, $context, $info) {
            return GraphQlControllerHelper::getNursingPracticeQuestion($args);
        }
    ]);
});

// Add mastered lesson
register_graphql_mutation( 'addUserMasteredLesson', [
	'inputFields' => [
		'userID' => [
			'type' => 'Number',
			'description' => __( 'ID of the user', 'wp-graphql' ),
		],
		'lessonID' => [
			'type' => 'Number',
			'description' => __( 'ID of the lesson to be added', 'wp-graphql' ),
		]
	],
	'outputFields' => [
		'updatedLessons' => [
			'type' => ['list_of' => 'Number'],
			'description' => __( 'Original array of lesson IDs', 'wp-graphql' ),
			'resolve' => function( $payload, $args, $context, $info ) {
            	return isset( $payload['updatedLessons'] ) ? $payload['updatedLessons'] : null;
			}
		],
	],
	'mutateAndGetPayload' => function( $input, $context, $info ) {
		$lessons = get_user_meta($input['userID'], 'completed_lessons', true);
		$lessons[] = $input['lessonID'];
		update_user_meta($input['userID'], 'completed_lessons', $lessons);

		return [
			'updatedLessons' => $lessons,
		];
	}
] );

// Remove mastered lesson
register_graphql_mutation( 'removeUserMasteredLesson', [
	'inputFields' => [
		'userID' => [
			'type' => 'Number',
			'description' => __( 'ID of the user', 'wp-graphql' ),
		],
		'lessonID' => [
			'type' => 'Number',
			'description' => __( 'ID of the lesson to be added', 'wp-graphql' ),
		]
	],
	'outputFields' => [
		'updatedLessons' => [
			'type' => ['list_of' => 'Number'],
			'description' => __( 'Original array of lesson IDs', 'wp-graphql' ),
			'resolve' => function( $payload, $args, $context, $info ) {
            	return isset( $payload['updatedLessons'] ) ? $payload['updatedLessons'] : null;
			}
		],
	],
	'mutateAndGetPayload' => function( $input, $context, $info ) {
		$lessons = get_user_meta($input['userID'], 'completed_lessons', true);
		$index = array_search($input['lessonID'], $lessons);
        unset($lessons[$index]);

		update_user_meta($input['userID'], 'completed_lessons', $lessons);

		return [
			'updatedLessons' => $lessons,
		];
	}
] );