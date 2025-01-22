<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use common\models\Task; 
use common\models\Answer; 
use common\models\Students; 
use common\models\User; 

class EmailController extends Controller
{
    public function actionSendEmailToStudents()
    {
        Yii::info('SendEmailToStudents command started', __METHOD__);
    
       // Calculate date range
        $today = date('Y-m-d');
        $oneWeekBeforeToday = date('Y-m-d', strtotime('-1 week'));

        // Find tasks within the calculated date range
        $tasks = Task::find()
            ->where(['<=', 'due_date', $today]) // due_date should be today or earlier
            ->andWhere(['>=', 'due_date', $oneWeekBeforeToday]) // due_date should be within 1 week before today
            ->all();
    
        Yii::info('Tasks to process: ' . count($tasks), __METHOD__);
    
        foreach ($tasks as $task) {
            Yii::info('Processing task: ' . $task->id, __METHOD__);
            
            // Find the student related to the task
            $stud = Students::find()->where(['user_id' => $task->user_id])->one();
            if ($stud) {
                $student_email = $stud->email;
    
                // Check if the email address is valid
                if (filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
                    $logoPath = Yii::getAlias('@webroot/udom.png');
                    $message = Yii::$app->mailer->compose();
                    $message->setTo($student_email);
                    $message->setFrom('mrsouth16@example.com'); // Set a proper sender email address
                    $message->setSubject('Reminder: Task Due Date Approaching');
                    $message->setHtmlBody('<div style="text-align: center;"><img src="' . $message->embed($logoPath) . '" alt="University Logo" style="display: block; margin: auto; width: 200px; height: auto;"></div><br><p>Dear student,</p><p>This is a reminder that the due date for the task "' . $task->subject . '" is approaching. Please complete it before the due date.</p><p>Best regards,<br>University Administration</p>');
    
                    // Try to send the email
                    try {
                        if ($message->send()) {
                            Yii::info('Email sent to: ' . $student_email, __METHOD__);
                            echo "Email sent successfully to {$student_email}.\n";
                        } else {
                            Yii::error('Failed to send email to: ' . $student_email, __METHOD__);
                            echo "Failed to send email to {$student_email}.\n";
                        }
                    } catch (\Exception $e) {
                        Yii::error('Exception while sending email to: ' . $student_email . ' - ' . $e->getMessage(), __METHOD__);
                        echo "Exception while sending email to {$student_email}: " . $e->getMessage() . "\n";
                    }
                } else {
                    Yii::info('Invalid email address: ' . $student_email, __METHOD__);
                    echo "Invalid email address for student.\n";
                }
            } else {
                Yii::info('No student found for task: ' . $task->id, __METHOD__);
                echo "No student found for task.\n";
            }
        }
    
        Yii::info('SendEmailToStudents command completed', __METHOD__);
        echo "sendEmailToStudents command completed.\n";
        return ExitCode::OK;
    }



    public function actionSendEmailToSupervisors()
    {
        Yii::info('SendEmailToSupervisors command started', __METHOD__);
        
        // Get all tasks with answers submitted by students
        $tasks = Task::find()
            ->joinWith('answers') // Assuming 'answers' is the relation name for answers table in Task model
            ->all();
        
        Yii::info('Tasks to process: ' . count($tasks), __METHOD__);
        
        foreach ($tasks as $task) {
            Yii::info('Processing task: ' . $task->id, __METHOD__);
            
            // Get the supervisor assigned to the task
            $supervisor = User::findOne($task->supervisor_id); // Adjust with actual relation and model
            
            // Ensure supervisor exists and has a valid email
            if ($supervisor && filter_var($supervisor->username, FILTER_VALIDATE_EMAIL)) {
                // Check if the task has been updated after the last answer submission
                $latestAnswerTime = $task->getLatestAnswerTime(); // Custom method to get the latest answer submission time
                $taskUpdatedTime = $task->updated_at; // Assuming 'updated_at' is the timestamp when task was last updated
                
                // Compare timestamps
                if ($taskUpdatedTime <= $latestAnswerTime) {
                    // No updates after the last answer submission
                    $logoPath = Yii::getAlias('@webroot/udom.png');
                    $message = Yii::$app->mailer->compose();
                    $message->setTo($supervisor->username);
                    $message->setFrom('mrsouth16@example.com'); // Set a proper sender email address
                    $message->setSubject('No Updates on Task: ' . $task->subject);
                    $message->setHtmlBody('<div style="text-align: center;"><img src="' . $message->embed($logoPath) . '" alt="University Logo" style="display: block; margin: auto; width: 200px; height: auto;"></div><br><p>Dear Supervisor,</p><p>This is to inform you that there have been no updates on the task "' . $task->subject . '" since the last submission by the student.</p><p>Please review the task status and follow up with the student if necessary.</p><p>Best regards,<br>University Administration</p>');
    
                    // Try to send the email
                    try {
                        if ($message->send()) {
                            Yii::info('Email sent to supervisor: ' . $supervisor->username, __METHOD__);
                            echo "Email sent successfully to {$supervisor->username} regarding task {$task->subject}.\n";
                        } else {
                            Yii::error('Failed to send email to supervisor: ' . $supervisor->username, __METHOD__);
                            echo "Failed to send email to supervisor {$supervisor->username} regarding task {$task->subject}.\n";
                        }
                    } catch (\Exception $e) {
                        Yii::error('Exception while sending email to supervisor: ' . $supervisor->email . ' - ' . $e->getMessage(), __METHOD__);
                        echo "Exception while sending email to supervisor {$supervisor->username}: " . $e->getMessage() . "\n";
                    }
                } else {
                    Yii::info('Updates found for task: ' . $task->id, __METHOD__);
                    echo "Updates found for task {$task->subject}.\n";
                }
            } else {
                Yii::info('No valid supervisor found for task: ' . $task->id, __METHOD__);
                echo "No valid supervisor found for task {$task->subject}.\n";
            }
        }
        
        Yii::info('SendEmailToSupervisors command completed', __METHOD__);
        echo "SendEmailToSupervisors command completed.\n";
        return ExitCode::OK;
    }
    
    
    
    // public function actionTestEmail()
    // {
    //     Yii::info('TestEmail command started', __METHOD__);

    //     $message = Yii::$app->mailer->compose()
    //         ->setTo('abdulhamidaley@gmail.com')
    //         ->setFrom('mrsouth16@example.com')
    //         ->setSubject('Test Email')
    //         ->setTextBody('This is a test email.')
    //         ->send();

    //     if ($message) {
    //         Yii::info('Test email sent successfully.', __METHOD__);
    //         echo "Test email sent successfully.\n";
    //     } else {
    //         Yii::error('Failed to send test email.', __METHOD__);
    //         echo "Failed to send test email.\n";
    //     }

    //     Yii::info('TestEmail command completed', __METHOD__);
    //     return ExitCode::OK;
    // }
}
