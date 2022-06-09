<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service\SyncList;

use Twilio\InstanceContext;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class SyncListPermissionContext extends InstanceContext {
    /**
     * Initialize the SyncListPermissionContext
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid Sync Service Instance SID or unique name.
     * @param string $listSid Sync List SID or unique name.
     * @param string $identity Identity of the user to whom the Sync List
     *                         Permission applies.
     * @return \Twilio\Rest\Sync\V1\Service\SyncList\SyncListPermissionContext 
     */
    public function __construct(Version $version, $serviceSid, $listSid, $identity) {
        parent::__construct($version);

        // Path Solution
        $this->solution = array(
            'serviceSid' => $serviceSid,
            'listSid' => $listSid,
            'identity' => $identity,
        );

        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Lists/' . rawurlencode($listSid) . '/Permissions/' . rawurlencode($identity) . '';
    }

    /**
     * Fetch a SyncListPermissionInstance
     * 
     * @return SyncListPermissionInstance Fetched SyncListPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new SyncListPermissionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['listSid'],
            $this->solution['identity']
        );
    }

    /**
     * Deletes the SyncListPermissionInstance
     * 
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() {
        return $this->version->delete('delete', $this->uri);
    }

    /**
     * Update the SyncListPermissionInstance
     * 
     * @param bool $read Read access.
     * @param bool $write Write access.
     * @param bool $manage Manage access.
     * @return SyncListPermissionInstance Updated SyncListPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($read, $write, $manage) {
        $data = Values::of(array(
            'Read' => Serialize::booleanToString($read),
            'Write' => Serialize::booleanToString($write),
            'Manage' => Serialize::booleanToString($manage),
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new SyncListPermissionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['listSid'],
            $this->solution['identity']
        );
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Sync.V1.SyncListPermissionContext ' . implode(' ', $context) . ']';
    }
}