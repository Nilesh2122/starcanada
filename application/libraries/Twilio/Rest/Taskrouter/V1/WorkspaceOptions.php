<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1;

use Twilio\Options;
use Twilio\Values;

abstract class WorkspaceOptions {
    /**
     * @param string $defaultActivitySid The ID of the Activity that will be used
     *                                   when new Workers are created in this
     *                                   Workspace.
     * @param string $eventCallbackUrl The Workspace will publish events to this
     *                                 URL.
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @param string $friendlyName Human readable description of this workspace
     * @param bool $multiTaskEnabled Enable or Disable Multitasking by passing
     *                               either true or False with the POST request.
     * @param string $timeoutActivitySid The ID of the Activity that will be
     *                                   assigned to a Worker when a Task
     *                                   reservation times out without a response.
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     * @return UpdateWorkspaceOptions Options builder
     */
    public static function update($defaultActivitySid = Values::NONE, $eventCallbackUrl = Values::NONE, $eventsFilter = Values::NONE, $friendlyName = Values::NONE, $multiTaskEnabled = Values::NONE, $timeoutActivitySid = Values::NONE, $prioritizeQueueOrder = Values::NONE) {
        return new UpdateWorkspaceOptions($defaultActivitySid, $eventCallbackUrl, $eventsFilter, $friendlyName, $multiTaskEnabled, $timeoutActivitySid, $prioritizeQueueOrder);
    }

    /**
     * @param string $friendlyName Filter by a workspace's friendly name.
     * @return ReadWorkspaceOptions Options builder
     */
    public static function read($friendlyName = Values::NONE) {
        return new ReadWorkspaceOptions($friendlyName);
    }

    /**
     * @param string $eventCallbackUrl If provided, the Workspace will publish
     *                                 events to this URL.
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @param bool $multiTaskEnabled Multi tasking allows workers to handle
     *                               multiple tasks simultaneously.
     * @param string $template One of the available template names.
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     * @return CreateWorkspaceOptions Options builder
     */
    public static function create($eventCallbackUrl = Values::NONE, $eventsFilter = Values::NONE, $multiTaskEnabled = Values::NONE, $template = Values::NONE, $prioritizeQueueOrder = Values::NONE) {
        return new CreateWorkspaceOptions($eventCallbackUrl, $eventsFilter, $multiTaskEnabled, $template, $prioritizeQueueOrder);
    }
}

class UpdateWorkspaceOptions extends Options {
    /**
     * @param string $defaultActivitySid The ID of the Activity that will be used
     *                                   when new Workers are created in this
     *                                   Workspace.
     * @param string $eventCallbackUrl The Workspace will publish events to this
     *                                 URL.
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @param string $friendlyName Human readable description of this workspace
     * @param bool $multiTaskEnabled Enable or Disable Multitasking by passing
     *                               either true or False with the POST request.
     * @param string $timeoutActivitySid The ID of the Activity that will be
     *                                   assigned to a Worker when a Task
     *                                   reservation times out without a response.
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     */
    public function __construct($defaultActivitySid = Values::NONE, $eventCallbackUrl = Values::NONE, $eventsFilter = Values::NONE, $friendlyName = Values::NONE, $multiTaskEnabled = Values::NONE, $timeoutActivitySid = Values::NONE, $prioritizeQueueOrder = Values::NONE) {
        $this->options['defaultActivitySid'] = $defaultActivitySid;
        $this->options['eventCallbackUrl'] = $eventCallbackUrl;
        $this->options['eventsFilter'] = $eventsFilter;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['multiTaskEnabled'] = $multiTaskEnabled;
        $this->options['timeoutActivitySid'] = $timeoutActivitySid;
        $this->options['prioritizeQueueOrder'] = $prioritizeQueueOrder;
    }

    /**
     * The ID of the Activity that will be used when new Workers are created in this Workspace.
     * 
     * @param string $defaultActivitySid The ID of the Activity that will be used
     *                                   when new Workers are created in this
     *                                   Workspace.
     * @return $this Fluent Builder
     */
    public function setDefaultActivitySid($defaultActivitySid) {
        $this->options['defaultActivitySid'] = $defaultActivitySid;
        return $this;
    }

    /**
     * The Workspace will publish events to this URL. You can use this to gather data for reporting. See [Events][/docs/taskrouter/api/events] for more information.
     * 
     * @param string $eventCallbackUrl The Workspace will publish events to this
     *                                 URL.
     * @return $this Fluent Builder
     */
    public function setEventCallbackUrl($eventCallbackUrl) {
        $this->options['eventCallbackUrl'] = $eventCallbackUrl;
        return $this;
    }

    /**
     * Use this parameter to receive webhooks on EventCallbackUrl for specific events on a workspace. For example if 'EventsFilter=task.created,task.canceled,worker.activity.update', then TaskRouter will webhook to EventCallbackUrl only when a task is created, canceled or a worker activity is updated.
     * 
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @return $this Fluent Builder
     */
    public function setEventsFilter($eventsFilter) {
        $this->options['eventsFilter'] = $eventsFilter;
        return $this;
    }

    /**
     * Human readable description of this workspace (for example "Sales Call Center" or "Customer Support Team")
     * 
     * @param string $friendlyName Human readable description of this workspace
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * Enable or Disable Multitasking by passing either *true* or *False* with the POST request. Learn more by visiting [Multitasking][/docs/taskrouter/multitasking].
     * 
     * @param bool $multiTaskEnabled Enable or Disable Multitasking by passing
     *                               either true or False with the POST request.
     * @return $this Fluent Builder
     */
    public function setMultiTaskEnabled($multiTaskEnabled) {
        $this->options['multiTaskEnabled'] = $multiTaskEnabled;
        return $this;
    }

    /**
     * The ID of the Activity that will be assigned to a Worker when a Task reservation times out without a response.
     * 
     * @param string $timeoutActivitySid The ID of the Activity that will be
     *                                   assigned to a Worker when a Task
     *                                   reservation times out without a response.
     * @return $this Fluent Builder
     */
    public function setTimeoutActivitySid($timeoutActivitySid) {
        $this->options['timeoutActivitySid'] = $timeoutActivitySid;
        return $this;
    }

    /**
     * Use this parameter to configure whether to prioritize LIFO or FIFO when workers are receiving Tasks from combination of LIFO and FIFO TaskQueues. Default is FIFO. [Click here][/docs/taskrouter/queue-ordering-last-first-out-lifo] to learn more about LIFO and the use of the parameter.
     * 
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     * @return $this Fluent Builder
     */
    public function setPrioritizeQueueOrder($prioritizeQueueOrder) {
        $this->options['prioritizeQueueOrder'] = $prioritizeQueueOrder;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Taskrouter.V1.UpdateWorkspaceOptions ' . implode(' ', $options) . ']';
    }
}

class ReadWorkspaceOptions extends Options {
    /**
     * @param string $friendlyName Filter by a workspace's friendly name.
     */
    public function __construct($friendlyName = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
    }

    /**
     * Filter by a workspace's friendly name. This is a human readable description of this Workspace (for example "Customer Support" or "2014 Election Campaign")
     * 
     * @param string $friendlyName Filter by a workspace's friendly name.
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Taskrouter.V1.ReadWorkspaceOptions ' . implode(' ', $options) . ']';
    }
}

class CreateWorkspaceOptions extends Options {
    /**
     * @param string $eventCallbackUrl If provided, the Workspace will publish
     *                                 events to this URL.
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @param bool $multiTaskEnabled Multi tasking allows workers to handle
     *                               multiple tasks simultaneously.
     * @param string $template One of the available template names.
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     */
    public function __construct($eventCallbackUrl = Values::NONE, $eventsFilter = Values::NONE, $multiTaskEnabled = Values::NONE, $template = Values::NONE, $prioritizeQueueOrder = Values::NONE) {
        $this->options['eventCallbackUrl'] = $eventCallbackUrl;
        $this->options['eventsFilter'] = $eventsFilter;
        $this->options['multiTaskEnabled'] = $multiTaskEnabled;
        $this->options['template'] = $template;
        $this->options['prioritizeQueueOrder'] = $prioritizeQueueOrder;
    }

    /**
     * If provided, the Workspace will publish events to this URL. You can use this to gather data for reporting. See Workspace Events for more information.
     * 
     * @param string $eventCallbackUrl If provided, the Workspace will publish
     *                                 events to this URL.
     * @return $this Fluent Builder
     */
    public function setEventCallbackUrl($eventCallbackUrl) {
        $this->options['eventCallbackUrl'] = $eventCallbackUrl;
        return $this;
    }

    /**
     * Use this parameter to receive webhooks on EventCallbackUrl for specific events on a workspace. For example if 'EventsFilter=task.created,task.canceled,worker.activity.update', then TaskRouter will webhook to EventCallbackUrl only when a task is created, canceled or a worker activity is updated.
     * 
     * @param string $eventsFilter Use this parameter to receive webhooks on
     *                             EventCallbackUrl for specific events on a
     *                             workspace.
     * @return $this Fluent Builder
     */
    public function setEventsFilter($eventsFilter) {
        $this->options['eventsFilter'] = $eventsFilter;
        return $this;
    }

    /**
     * Multi tasking allows workers to handle multiple tasks simultaneously. When enabled (MultiTaskEnabled=true), each worker will be eligible to receive parallel reservations up to the per-channel maximums defined in the Workers section. Default is disabled (MultiTaskEnabled=false), where each worker will only receive a new reservation when the previous task is completed. Learn more by visiting [Multitasking][/docs/taskrouter/multitasking].
     * 
     * @param bool $multiTaskEnabled Multi tasking allows workers to handle
     *                               multiple tasks simultaneously.
     * @return $this Fluent Builder
     */
    public function setMultiTaskEnabled($multiTaskEnabled) {
        $this->options['multiTaskEnabled'] = $multiTaskEnabled;
        return $this;
    }

    /**
     * One of the available template names. Will pre-configure this Workspace with the Workflow and Activities specified in the template. "NONE" will create a Workspace with a set of default activities and nothing else. "FIFO" will configure TaskRouter with a set of default activities and a single task queue for first-in, first-out distribution, useful if you want to see a simple TaskRouter configuration when getting started. Defaults to "NONE".
     * 
     * @param string $template One of the available template names.
     * @return $this Fluent Builder
     */
    public function setTemplate($template) {
        $this->options['template'] = $template;
        return $this;
    }

    /**
     * Use this parameter to configure whether to prioritize LIFO or FIFO when workers are receiving Tasks from combination of LIFO and FIFO TaskQueues. Default is FIFO. [Click here][/docs/taskrouter/queue-ordering-last-first-out-lifo] to learn more about LIFO and the use of the parameter.
     * 
     * @param string $prioritizeQueueOrder Use this parameter to configure whether
     *                                     to prioritize LIFO or FIFO when workers
     *                                     are receiving Tasks from combination of
     *                                     LIFO and FIFO TaskQueues.
     * @return $this Fluent Builder
     */
    public function setPrioritizeQueueOrder($prioritizeQueueOrder) {
        $this->options['prioritizeQueueOrder'] = $prioritizeQueueOrder;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Taskrouter.V1.CreateWorkspaceOptions ' . implode(' ', $options) . ']';
    }
}