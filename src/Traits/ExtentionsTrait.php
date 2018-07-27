<?php

namespace romanzipp\Twitch\Traits;

use romanzipp\Twitch\Helpers\Paginator;
use romanzipp\Twitch\Result;

trait ExtentionsTrait
{
    /**
     * Get currently authed user's extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference#get-user-extensions"
     */
    public function getAuthedUserExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }
        return $this->get('users/extensions/list', [], null);
    }

    /**
     * Get currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference#get-user-active-extensions"
     */
    public function getAuthedUserActiveExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->get('users/extensions', [], null);
    }

    /**
     * Disable all Extensions of the currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     */
    public function DisableAllExtensions(string $token = null): Result
    {
        if ($token !== null) {
            $this->withToken($token);
        }

        return $this->UpdateUserExtensions(null, null, true);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given id with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @param  string $parameter Id of the Extension that should be deactivated
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     */
    public function DisableUserExtensionById(string $token = null, string $parameter = null) : Result
    {
        return $this->UpdateUserExtensions("id", $parameter, false);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given Name with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $token Twitch OAuth Token
     * @param  string $parameter Name of the Extension that should be deactivated
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     */
    public function DisableUserExtensionByName(string $token = null, string $parameter = null) : Result
    {
        return $this->UpdateUserExtensions("name", $parameter, false);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given method and parameter with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required
     * @param  string $method    method that will be used to disable extensions
     * @param  string $parameter Parameter that will be used to disable Extensions
     * @param  bool   $disabled  bool if the set value should be false
     * @return Result Result object
     * @see    "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     */
    public function UpdateUserExtensions(string $method = null, string $parameter = null, bool $disabled = false) : Result
    {
        $apiresult = $this->getAuthedUserActiveExtensions();

        $extentions = (array) $apiresult->data;

        $data = (object) [
            "panel" => new \stdClass(),
            "overlay" => new \stdClass(),
            "component" => new \stdClass()
        ];

        $panels = $extentions['panel'];
        $overlays = $extentions['overlay'];
        $components = $extentions['component'];

        $data = (object) [
            "panel" => $panels,
            "overlay" => $overlays,
            "component" => $components
        ];

        $i = 1;
        foreach ($data->panel as $key => $value) {
            if ($disabled === true) {
                $data->panel->$i->active = false;
            } else {
                if (isset($value->$method)) {
                    if ($value->$method <=> $parameter) {
                        $data->panel->$i->active = false;
                    } else {
                        $data->panel->$i->active = $value->active;
                    }
                } else {
                    $data->panel->$i = $value;
                }
            }
            $i++;
        }

        $i = 1;
        foreach ($data->overlay as $key => $value) {
            if ($disabled === true) {
                $data->overlay->$i->active = false;
            } else {
                if (isset($value->$method)) {
                    if ($value->$method <=> $parameter) {
                        $data->overlay->$i->active = false;
                    } else {
                        $data->overlay->$i->active = $value->active;
                    }
                } else {
                    $data->overlay->$i = $value;
                }
            }
            $i++;
        }

        $i = 1;
        foreach ($data->component as $key => $value) {
            if ($disabled === true) {
                $data->component->$i->active = false;
            } else {
                if (isset($value->$method)) {
                    if ($value->$method <=> $parameter) {
                        $data->component->$i->active = false;
                    } else {
                        $data->component->$i->active = $value->active;
                    }
                } else {
                    $data->component->$i = $value;
                }
            }
            $i++;
        }

        $parameter = (array) $data;

        return $this->json('users/extensions', $parameter);
    }

    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null);

    abstract public function json(string $path = '', array $parameters = []);

    abstract public function withToken(string $token);
}
