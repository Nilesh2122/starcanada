<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace;

use Twilio\Options;
use Twilio\Values;

abstract class ActivityOptions {
    /**
     * @param string $friendlyName A human-readable name for the Activity, such as
     *                             'on-call', 'break', 'email', etc.
     * @return UpdateActivityOptions Options builder
     */
    public static function update($friendlyName = Values::NONE) {
        return new UpdateActivityOptions($friendlyName);
    }

    /**
     * @param string $friendlyName Filter by an Activity's friendly name
     * @param string $available Filter by activities that are available or
     *                          unavailable.
     * @return ReadActivityOptions Options builder
     */
    public static function read($friendlyName = Values::NONE, $available = Values::NONE) {
        return new ReadActivityOptions($friendlyName, $available);
    }

    /**
     * @param bool $available Boolean value indicating whether the worker should be
     *                        eligible to receive a Task when they occupy this
     *                        Activity.
     * @return CreateActivityOptions Options builder
     */
    public static function create($available = Values::NONE) {
        return new CreateActivityOptions($available);
    }
}

class UpdateActivityOptions extends Options {
    /**
     * @param string $friendlyName A human-readable name for the Activity, such as
     *                             'on-call', 'break', 'email', etc.
     */
    public function __construct($friendlyName = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
    }

    /**
     * A human-readable name for the Activity, such as 'on-call', 'break', 'email', etc. These names will be used to calculate and expose statistics about workers, and give you visibility into the state of each of your workers.
     * 
     * @param string $friendlyName A human-readable name for the Activity, such as
     *                             'on-call', 'break', 'email', etc.
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
        return '[Twilio.Taskrouter.V1.UpdateActivityOptions ' . implode(' ', $options) . ']';
    }
}

class ReadActivityOptions extends Options {
    /**
     * @param string $friendlyName Filter by an Activity's friendly name
     * @param string $available Filter by activities that are available or
     *                          unavailable.
     */
    public function __construct($friendlyName = Values::NONE, $available = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['available'] = $available;
    }

    /**
     * Filter by an Activity's friendly name
     * 
     * @param string $friendlyName Filter by an Activity's friendly name
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * Filter by activities that are available or unavailable. (Note: This can be 'true', '1'' or 'yes' to indicate a true value. All other values will represent false)
     * 
     * @param string $available Filter by activities that are available or
     *                          unavailable.
     * @return $this Fluent Builder
     */
    public function setAvailable($available) {
        $this->options['available'] = $available;
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
        return '[Twilio.Taskrouter.V1.ReadActivityOptions ' . implode(' ', $options) . ']';
    }
}

class CreateActivityOptions extends Options {
    /**
     * @param bool $available Boolean value indicating whether the worker should be
     *                        eligible to receive a Task when they occupy this
     *                        Activity.
     */
    public function __construct($available = Values::NONE) {
        $this->options['available'] = $available;
    }

    /**
     * Boolean value indicating whether the worker should be eligible to receive a Task when they occupy this Activity. For example, a call center might have an activity named 'On Call' with an availability set to 'false'. Note: This can be 'true', '1' or 'yes' to indicate a true value. All other values will represent false. Defaults to false.
     * 
     * @param bool $available Boolean value indicating whether the worker should be
     *                        eligible to receive a Task when they occupy this
     *                        Activity.
     * @return $this Fluent Builder
     */
    public function setAvailable($available) {
        $this->options['available'] = $available;
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
        return '[Twilio.Taskrouter.V1.CreateActivityOptions ' . implode(' ', $options) . ']';
    }
}