<?php

use Core\Traits\Users\Data\CourseData;
use Core\Posts\Module;
use Core\Posts\Lesson;
use Core\Posts\Course;
use Core\Posts\Instructor;

/**
 * Class CoursesGraphQLController
 */
class CoursesGraphQLController
{
    use CourseData;

    /**
     * Get courses list
     *
     * @param $termIdData
     *
     * @return array|array[]
     */
    function getCourseList($termIdData)
    {

        $termId = get_slug_term_id($termIdData, 'stage');

        $courseIds = $this->queryCourses($termId);

        $courseList = [];
        foreach ($courseIds as $courseId) {
            $course = new Course($courseId);
            $moduleIds = $course->getModules(); // Why does this return Strings when it is supposed to be Ints?
            $lessonIds = $course->getLessons();
            $publishedLessons = 0;

            $moduleList = [];
            foreach ($moduleIds as $moduleId) {
                $module = new Module($moduleId);
                $lessonIds = $module->getLessons();

                $lessonList = [];
                foreach ($lessonIds as $lessonId) {
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
                        'questions' => $questions,
                        'counters' => [
                            'questionCount' => count($questions)
                        ]
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
                'featuredImage' => get_the_post_thumbnail_url($course->getId(), 'full'),
                'description' => $course->description(),
                'modules' => $moduleList,
                'courseLessons' => $lessonIds,
                'publishedLessons' => $publishedLessons,
            ];
        }
        return $courseList;
    }

    /**
     * get Course Detail
     *
     * @param $request
     * @param null $user
     * @return array[]
     */
    static function getCourse($request, $user = null)
    {
        $course = new Course($request['id']);

        $courseResponseData = [];
        $courseResponseData['id'] = $course->getId();
        $courseResponseData['title'] = $course->title();
        if ($user && $user->ID !== 0) {
            $courseResponseData['completedPercent'] =
                $course->studentMasteryPercentage($course->getPublishedLessons(), $user->completedLessonIds());
        } else {
            $courseResponseData['completedPercent'] = 0;
        }
        $courseResponseData['instructors'] = [];
        $courseResponseData['modules'] = [];
        $courseResponseData['counters'] = [
            'bookCount' => 0,
            'caseStudyCount' => 0,
            'carePlanCount' => 0,
            'cheatSheetCount' => 0,
            'imageCount' => 0,
            'lessonsCount' => 0,
            'mnemonicCount' => 0,
            'picmonicCount' => 0,
            'questionCount' => 0
        ];

        $instructorIds = $course->instructors();
        foreach ($instructorIds as $instructorId) {
            $instructor = new Instructor($instructorId);
            $courseResponseData['instructors'][] = [
                'id' => $instructorId,
                'name' => $instructor->getName(),
                'thumbnail' => $instructor->getThumbnail(),
                'degree' => $instructor->getDegree(),
                'bio' => $instructor->getBio(),
                'license' => $instructor->getLicense(),
                'certifications' => $instructor->getCertifications()
            ];
        }

        $moduleIds = $course->getModules();

        foreach ($moduleIds as $moduleId) {
            $module = new Module($moduleId);
            $moduleData = [
                'id' => $module->getId(),
                'title' => $module->title(),
                'counters' => []
            ];

            $lessonIds = $module->getPublishedLessons();
            $lessonsData = [];
            foreach ($lessonIds as $lessonId) {
                $courseResponseData['counters']['lessonsCount']++;
                $lesson = new Lesson($lessonId);
                $questions = $lesson->getQuestions();
                $questionCount = count($questions);
                $lessonData = [
                    'id' => $lesson->getId(),
                    'title' => $lesson->title(),
                    'counters' => [
                        'bookCount' => 0,
                        'caseStudyCount' => 0,
                        'carePlanCount' => 0,
                        'cheatSheetCount' => 0,
                        'imageCount' => 0,
                        'lessonsCount' => 1,
                        'mnemonicCount' => 0,
                        'picmonicCount' => 0,
                        'questionCount' => $questionCount
                    ],
                    'questions' => []
                ];
                $courseResponseData['counters']['questionCount'] += $questionCount;
                $attachedStudyToolTypes = $lesson->studyToolTypes();
                foreach ($attachedStudyToolTypes as $key => $value) {
                    switch ($key) {
                        case 'book':
                            $lessonData['counters']['bookCount'] = count($value);
                            $courseResponseData['counters']['bookCount'] += $lessonData['counters']['bookCount'];
                            break;
                        case 'care_plan':
                            $lessonData['counters']['carePlanCount'] = count($value);
                            $courseResponseData['counters']['carePlanCount'] += $lessonData['counters']['carePlanCount'];
                            break;
                        case 'case_study':
                            $lessonData['counters']['caseStudyCount'] = count($value);
                            $courseResponseData['counters']['caseStudyCount'] += $lessonData['counters']['caseStudyCount'];
                            break;
                        case 'cheat_sheet':
                            $lessonData['counters']['cheatSheetCount'] = count($value);
                            $courseResponseData['counters']['cheatSheetCount'] += $lessonData['counters']['cheatSheetCount'];
                            break;
                        case 'image':
                            $lessonData['counters']['imageCount'] = count($value);
                            $courseResponseData['counters']['imageCount'] += $lessonData['counters']['imageCount'];
                            break;
                        case 'mnemonic':
                            $lessonData['counters']['mnemonicCount'] = count($value);
                            $courseResponseData['counters']['mnemonicCount'] += $lessonData['counters']['mnemonicCount'];
                            break;
                        case 'picmonic':
                            $lessonData['counters']['picmonicCount'] = count($value);
                            $courseResponseData['counters']['picmonicCount'] += $lessonData['counters']['picmonicCount'];
                            break;
                        default:
                            break;
                    }
                }
                $lessonsData[] = $lessonData;
            }
            $moduleData['lessons'] = $lessonsData;
            $courseResponseData['modules'][] = $moduleData;
        }

        return $courseResponseData;
    }
}