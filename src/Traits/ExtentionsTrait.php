<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait ExtentionsTrait
{
    /**
     * Get currently authed user's extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token    Twitch OAuth Token
     * @return Result           Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-user-extensions
     */
    public function getAuthedUserExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }
        
        return $this->get('users/extensions/list');
    }

    /**
     * Get currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token    Twitch OAuth Token
     * @return Result           Result object
     * @see    https://dev.twitch.tv/docs/api/reference#get-user-active-extensions
     */
    public function getAuthedUserActiveExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->get('users/extensions');
    }

    /**
     * Disable all Extensions of the currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token    Twitch OAuth Token
     * @return Result           Result object
     * @see    "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     */
    public function disableAllExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->updateUserExtensions(null, null, true);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given id with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token        Twitch OAuth Token
     * @param  string $parameter    Id of the Extension that should be deactivated
     * @return Result               Result object
     * @see    https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     */
    public function disableUserExtensionById(string $token = null, string $parameter = null): Result
    {
        return $this->updateUserExtensions('id', $parameter, false);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given Name with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token        Twitch OAuth Token
     * @param  string $parameter    Name of the Extension that should be deactivated
     * @return Result               Result object
     * @see    https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     */
    public function disableUserExtensionByName(string $token = null, string $parameter = null): Result
    {
        return $this->updateUserExtensions('name', $parameter, false);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given method and parameter with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $method    Method that will be used to disable extensions
     * @param  string $parameter Parameter that will be used to disable Extensions
     * @param  bool   $disabled  Weather the set value should be false
     * @return Result            Result object
     * @see    https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     */
    public function updateUserExtensions(string $method = null, string $parameter = null, bool $disabled = false): Result
    {
        $extensionsResult = $this->getAuthedUserActiveExtensions();

        $extensions = (array) $extensionsResult->data;

        $data = (object) [
            'panel' => $extensions['panel'],
            'overlay' => $extensions['overlay'],
            'component' => $extensions['component'],
        ];

        $processType = function (string $type) use (&$data, $method, $parameter, $disabled) {

            $i = 1;

            foreach ($data->$type as $key => $value) {

                if ($disabled === true) {

                    $data->$type->$i->active = false;

                } else {

                    if (isset($value->$method)) {

                        if ($value->$method <=> $parameter) {
                            $data->$type->$i->active = false;
                        } else {
                            $data->$type->$i->active = $value->active;
                        }

                    } else {
                        $data->$type->$i = $value;
                    }
                }

                $i++;
            }
        };

        $processType('panel');
        $processType('overlay');
        $processType('component');

        return $this->json('PUT', 'users/extensions', (array) $data);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function json(string $method, string $path = '', array $body = null);

    abstract public function withToken(string $token);
}
