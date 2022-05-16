<?php

use Core\Views\StudyPlans\StudyPlanRows;
use Core\Traits\Users\Data\CourseData;
use Core\Posts\Module;
use Core\Posts\Lesson;
use Core\Posts\Course;

/**
 * Class GraphQlControllerHelper
 */
class GraphQlControllerHelper {
    use CourseData;
    /**
     * Get Nursing Practice Question
     *
     * @param $request
     * @return array
     */
    static function getNursingPracticeQuestion($request) {
        $return_posts = array();
        $max_limit = 1000;
        $loop_count = 0;
        do{
            $query_args = array(
                'posts_per_page' => $max_limit,
                'post_type' => 'practice_question',
                'offset' => $loop_count*$max_limit
            );
            $loop_count++;
            if( isset($request['limit']) ){
                $query_args['posts_per_page'] = $request['limit'];
                $query_args['orderby'] = 'rand';
            }
            if( isset($request['questions']) ){
                $query_args['meta_query'] = array(
                    array(
                        'key' => 'unique_question_id',
                        'value' => $request['questions'],
                        'compare' => 'IN'
                    )
                );
            }
            if( isset($request['nursing-category']) || isset($request['teas-category']) || isset($request['hesi-category']) ){
                $query_args['tax_query'] = array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'nrsng_category',
                        'field' => 'id',
                        'terms' => $request['nursing-category']
                    ),
                    array(
                        'taxonomy' => 'hesi_category',
                        'field' => 'id',
                        'terms' => $request['hesi-category']
                    ),
                    array(
                        'taxonomy' => 'teas_category',
                        'field' => 'id',
                        'terms' => $request['teas-category']
                    ),
                );
            }


            if( isset($request['id']) ){
                $query_args['p'] = $request['id'];
            }
            $all_posts = query_posts($query_args);
            $result_count = $all_posts->post_count;
            foreach ($all_posts as $post_data) {
                $single_post = array();
                $single_post['id'] = $post_data->ID;
                $single_post['post_modified'] = $post_data->post_modified;
                $acf = get_fields($post_data->ID);
                $answers = array();
                foreach ($acf['answers'] as $key) {
                    $answers[] = array('answer'=>$key['txt']);

                }
                $acf['answers'] = $answers;
                $acf['type']== strtolower($acf['type']);
                $acf['answer_type'] = $acf['type'];
                $answer_text = "";
                if($acf['answer_rationales']){
                    foreach ($acf['answer_rationales'] as $seperate_rationale) {
                        //return $seperate_rationale;
                        $answer_text .= $seperate_rationale['answer']['txt'] . " is " . ( empty($seperate_rationale['correct_status']) ? 'incorrect' : 'correct' ) . "\n" . $seperate_rationale['rationale'] . "\n\n";
                    }
                }
                else{
                    $answer_text = $acf['answer_desc'];
                }

                $acf['rationale'] = $answer_text;
                if($acf['type']=='single'){
                    $acf['correct_answer'] = $acf['correct_answer'][0];
                }
                else{
                    $answer_imploded='';
                    foreach ($acf['correct_answers'] as $answer_value) {
                        $answer_imploded .= $answer_value['txt'];
                    }
                    $acf['correct_answer'] = $answer_imploded;
                }

                if( !isset($acf['correct answers']) ){
                    if( $count_value = get_post_meta( $post_data->ID, 'correct answers', true ) ){
                        $acf['correct answers'] = $count_value;
                    }
                    else{
                        $acf['correct answers'] = 0;
                    }
                }
                if( !isset($acf['total answers']) ){
                    if( $count_value = get_post_meta( $post_data->ID, 'total answers', true ) ){
                        $acf['total answers'] = $count_value;
                    }
                    else{
                        $acf['total answers'] = 0;
                    }
                }
                if( !isset($acf['percentright']) ){
                    if( $count_value = get_post_meta( $post_data->ID, 'percentright', true ) ){
                        $acf['percentright'] = $count_value;
                    }
                    else{
                        $acf['percentright'] = 0;
                    }
                }
                $single_post['acf'] = $acf;
                $single_post['nclex-category'] = wp_get_post_terms($post_data->ID, 'practice_question_nclex', array("fields" => "id=>name"));
                $single_post['nursing-category'] = wp_get_post_terms($post_data->ID, 'nrsng_category', array("fields" => "id=>name"));
                $single_post['hesi-category'] = wp_get_post_terms($post_data->ID, 'hesi_category', array("fields" => "id=>name"));
                $single_post['teas-category'] = wp_get_post_terms($post_data->ID, 'teas_category', array("fields" => "id=>name"));

                $return_posts[] =$single_post;
            }
        }while($result_count>=$max_limit);

        return $return_posts;
    }

    /**
     * Get User Study Plan
     *
     * @param $userId
     * @return \Core\Views\StudyPlans\StudyPlansFactory
     */
    static function getUserStudyPlan($userId) {
        $user = new Core\User($userId);
        $studyPlansReport = $user->getStudyPlans();
        $educatorStudy = new \ED\Reports\StudyPlans\EducatorStudyPlans($studyPlansReport);
        $processedStudyPlans= $educatorStudy->getProcessedStudyPlans();
        $userStudyPlansList = [];
        foreach ($processedStudyPlans as $key => $value) {
            $value['id'] = $key;
            $userStudyPlansList[] = $value;
        }
        return $userStudyPlansList;
    }

    /**
     * Get courses list
     *
     * @param $termIdData
     *
     * @return array|array[]
     */
    function getCourseList($termIdData) {

        $termId = get_slug_term_id($termIdData, 'stage');

        $courseIds = $this->queryCourses($termId);

        $courseList = [];
        foreach($courseIds as $courseId) {
            $course = new Course($courseId);
            $moduleIds = $course->getModules(); // Why does this return Strings when it is supposed to be Ints?
            $lessonIds = $course->getLessons();
            $publishedLessons = 0;

            $moduleList = [];
            foreach($moduleIds as $moduleId) {
                $module = new Module($moduleId);
                $lessonIds = $module->getLessons();

                $lessonList = [];
                foreach($lessonIds as $lessonId) {
                    $publishedLessons += 1;
                    $lesson = new Lesson((int)$lessonId);
                    $studyToolTypes = $lesson->studyToolTypes();
                    $questions = $lesson->getQuestions();

                    $studyToolList = [];
                    if (is_array($studyToolTypes)) {
                        foreach ($studyToolTypes as $type => $ids) {
                            $studyToolList[] = [
                                'count' => count($ids),
                                'type' => post_label($type),
                            ];
                        }
                    }

                    $lessonList[] = [
                        'id' => $lessonId,
                        'title' => get_the_title($lessonId),
                        'studyTools' => $studyToolList,
                        'questions' => count($questions),
                    ];
                }

                $moduleList[] = [
                    'id' => $moduleId,
                    'title' => get_the_title($moduleId),
                    'lessons' => $lessonList,
                ];
            }

            $courseList[] = [
                'id' => $courseId,
                'title' => get_the_title($course->getId()),
                'featured_image' => get_the_post_thumbnail_url( $course->getId(), 'full' ),
                'description' => $course->description(),
                'modules' => $moduleList,
                'courseLessons' => $lessonIds,
                'publishedLessons' => $publishedLessons,
            ];
        }
        return $courseList;
    }
}
