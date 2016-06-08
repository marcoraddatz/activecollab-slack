<?php

/**
 * @param $object
 * @throws NotImplementedError
 */
function slack_handle_on_object_completed($object) {

    if ($object instanceof Task) {

        $project    = $object->getProject();
        //changed by MUCKY - Use getCustomField3 for channel
        $channel    = slack_get_channel( $project->getCustomField3() );
        //changed by MUCKY - No multiple Teams, so we get rid of getCustomField2()
        $token      = slack_get_token();

        if ($channel && $token) {

            $task_id    = $object->getTaskId();
            $task_name  = $object->getName();
            $task_url   = $object->getViewUrl();

            // There's no object named getCompletedBy()...
            $user_name  = $object->getCompletedByName();

            // @todo make this an object...
            if ($slack_user = slack_get_user_by_email($object->getCompletedByEmail(), $token)) {
                $user_name = "<@{$slack_user['id']}>";
            }

            $message = "Task completed *<{$task_url}|#{$task_id}: {$task_name}>* by *{$user_name}*";

            slack_post_message($message, $channel, $token);
        }
    }
}