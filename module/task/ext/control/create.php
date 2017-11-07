<?php
include '../../control.php';

class myTask extends task
{
    public function create($projectID = 0, $storyID = 0, $moduleID = 0, $taskID = 0)
    {
        $task = new stdClass();
        $task->module      = $moduleID;
        $task->assignedTo  = '';
        $task->name        = '';
        $task->story       = $storyID;
        $task->type        = '';
        $task->pri         = '';
        $task->estimate    = '';
        $task->desc        = '';
        $task->estStarted  = '';
        $task->deadline    = '';
        $task->mailto      = '';
        $task->color       = '';
        if($taskID > 0)
        {
            $task      = $this->task->getByID($taskID);
            $projectID = $task->project;
        }

        $project   = $this->project->getById($projectID); 
        $taskLink  = $this->createLink('project', 'browse', "projectID=$projectID&tab=task");
        $storyLink = $this->session->storyList ? $this->session->storyList : $this->createLink('project', 'story', "projectID=$projectID");

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $project->id);

        if(!empty($_POST))
        {
            $response['result']  = 'success';
            $response['message'] = '';

            $tasksID = $this->task->create($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            /* if the count of tasksID is 1 then check exists. */
            if(count($tasksID) == 1)
            {
                $taskID = current($tasksID);
                if($taskID['status'] == 'exists')
                {
                    $response['locate']  = $this->createLink('task', 'view', "taskID={$taskID['id']}");
                    $response['message'] = sprintf($this->lang->duplicate, $this->lang->task->common);
                    $this->send($response);
                }
            }

            /* Create actions. */
            $this->loadModel('action');
            foreach($tasksID as $taskID)
            {
                /* if status is exists then this task has exists not new create. */
                if($taskID['status'] == 'exists') continue;

                $taskID   = $taskID['id'];
                $actionID = $this->action->create('task', $taskID, 'Opened', '');
                $this->task->sendmail($taskID, $actionID);
            }

            /* If link from no head then reload*/
            if(isonlybody())
            {
                $response['locate'] = 'reload';
                $response['target'] = 'parent';
                $this->send($response);
            }

            /* Locate the browser. */
            if($this->post->after == 'continueAdding')
            {
                $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
                $response['locate']  = $this->createLink('task', 'create', "projectID=$projectID&storyID={$this->post->story}&moduleID=$moduleID");
                $this->send($response);
            }
            elseif($this->post->after == 'toTaskList')
            {
                $response['locate'] = $taskLink;
                $this->send($response);
            }
            elseif($this->post->after == 'toStoryList')
            {
                $response['locate'] = $storyLink;
                $this->send($response);
            }
            else
            {
                $response['locate'] = $taskLink;
                $this->send($response);
            }
        }

        $users            = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $moduleIdList     = $this->tree->getAllChildID($moduleID);
        $stories          = $this->story->getProjectStoryPairs($projectID, 0, 0, $moduleIdList);
        $members          = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($projectID);

        $title      = $project->name . $this->lang->colon . $this->lang->task->create;
        $position[] = html::a($taskLink, $project->name);
        $position[] = $this->lang->task->common;
        $position[] = $this->lang->task->create;

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $this->config->task->custom->createFields;

        $this->view->title            = $title;
        $this->view->position         = $position;
        $this->view->project          = $project;
        $this->view->task             = $task;
        $this->view->users            = $users;
        $this->view->stories          = $stories;
        $this->view->members          = $members;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->display();
    }
}

?>