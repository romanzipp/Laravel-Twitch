<?php

namespace romanzipp\Twitch\Concerns\Api;

use romanzipp\Twitch\Concerns\Operations\AbstractOperationsTrait;
use romanzipp\Twitch\Concerns\Operations\AbstractValidationTrait;
use romanzipp\Twitch\Objects\Paginator;
use romanzipp\Twitch\Result;

trait ExtensionsTrait
{
    use AbstractValidationTrait;
    use AbstractOperationsTrait;

    /**
     * Get currently authed user's extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-user-extensions
     *
     * @return Result Result instance
     */
    public function getAuthedUserExtensions(): Result
    {
        return $this->get('users/extensions/list');
    }

    /**
     * Get currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-user-active-extensions
     *
     * @return Result Result instance
     */
    public function getAuthedUserActiveExtensions(): Result
    {
        return $this->get('users/extensions');
    }

    /**
     * Disable all Extensions of the currently authed user's active extensions with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see "https://dev.twitch.tv/docs/api/reference/#update-user-extensions"
     *
     * @return Result Result instance
     */
    public function disableAllExtensions(): Result
    {
        return $this->updateUserExtensions(null, null, true);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given id with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     *
     * @param string|null $parameter Id of the Extension that should be deactivated
     *
     * @return Result Result instance
     */
    public function disableUserExtensionById(string $parameter = null): Result
    {
        return $this->updateUserExtensions('id', $parameter, false);
    }

    /**
     * Disables all Extensions of the currently authed user's active extensions, that have the given Name with Bearer Token
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     *
     * @param string|null $parameter Name of the Extension that should be deactivated
     *
     * @return Result Result instance
     */
    public function disableUserExtensionByName(string $parameter = null): Result
    {
        return $this->updateUserExtensions('name', $parameter, false);
    }

    /**
     * Updates the activation state, extension ID, and/or version number of installed extensions for a specified user, identified by a Bearer token.
     * If you try to activate a given extension under multiple extension types, the last write wins (and there is no guarantee of write order).
     * Note: Bearer OAuth Token and the state "user:edit:broadcast" are both required.
     *
     * @see https://dev.twitch.tv/docs/api/reference/#update-user-extensions
     *
     * @param string|null $method Method that will be used to disable extensions
     * @param string|null $parameter Parameter that will be used to disable Extensions
     * @param bool $disabled Weather the set value should be false
     *
     * @return Result Result instance
     */
    public function updateUserExtensions(string $method = null, string $parameter = null, bool $disabled = false): Result
    {
        $extensionsResult = $this->getAuthedUserActiveExtensions();

        $extensions = (array) $extensionsResult->data();

        $data = (object) [
            'panel' => $extensions['panel'],
            'overlay' => $extensions['overlay'],
            'component' => $extensions['component'],
        ];

        $processType = function (string $type) use (&$data, $method, $parameter, $disabled) {
            $i = 1;

            foreach ($data->$type as $key => $value) {
                if (true === $disabled) {
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

                ++$i;
            }
        };

        $processType('panel');
        $processType('overlay');
        $processType('component');

        return $this->put('users/extensions', [], null, (array) $data);
    }

    /**
     * Gets the specified configuration segment from the specified extension.
     *
     * NOTE: You can retrieve each segment a maximum of 20 times per minute.
     * If you exceed the limit, the request returns HTTP status code 429.
     * To determine when you may resume making requests, see the Ratelimit-Reset response header.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-configuration-segment
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getExtensionConfigurationSegment(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id', 'extension_id', 'segment']);

        return $this->get('extensions/configurations');
    }

    /**
     * Sets a single configuration segment of any type. The segment type is specified as a body parameter.
     *
     * Each segment is limited to 5 KB and can be set at most 20 times per minute.
     * Updates to this data are not delivered to Extensions that have already been rendered.
     *
     * @see https://dev.twitch.tv/docs/api/reference#set-extension-configuration-segment
     *
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function setExtensionConfigurationSegment(array $body = []): Result
    {
        $this->validateRequired($body, ['extension_id', 'segment']);

        return $this->put('extensions/configurations');
    }

    /**
     * Enable activation of a specified Extension, after any required broadcaster configuration is correct.
     * The Extension is identified by a client ID value assigned to the Extension when it is created.
     * This is for Extensions that require broadcaster configuration before activation.
     * Use this if, in Extension Capabilities, you select Custom/My Own Service.
     *
     * You enforce required broadcaster configuration with a required_configuration string in the Extension manifest.
     * The contents of this string can be whatever you want. Once your EBS determines that the Extension is correctly configured on a channel,
     * use this endpoint to provide that same configuration string, which enables activation on the channel.
     * The endpoint URL includes the channel ID of the page where the Extension is iframe embedded.
     *
     * If a future version of the Extension requires a different configuration, change the required_configuration string in your manifest.
     * When the new version is released, broadcasters will be required to re-configure that new version.
     *
     * @see https://dev.twitch.tv/docs/api/reference#set-extension-required-configuration
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function setExtensionRequiredConfiguration(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);
        $this->validateRequired($body, ['extension_id', 'extension_version', 'required_configuration']);

        return $this->put('extensions/required_configuration', $parameters, null, $body);
    }

    /**
     * Twitch provides a publish-subscribe system for your EBS to communicate with both the broadcaster and viewers.
     * Calling this endpoint forwards your message using the same mechanism as the send JavaScript helper function.
     * A message can be sent to either a specified channel or globally (all channels on which your extension is active).
     *
     * Extension PubSub has a rate limit of 100 requests per minute for a combination of Extension client ID and broadcaster ID.
     *
     * @see https://dev.twitch.tv/docs/api/reference#send-extension-pubsub-message
     *
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function sendExtensionPubSubMessage(array $body = []): Result
    {
        $this->validateRequired($body, ['target', 'broadcaster_id', 'is_global_broadcast', 'message']);

        return $this->post('extensions/pubsub', [], null, $body);
    }

    /**
     * Returns one page of live channels that have installed or activated a specific Extension,
     * identified by a client ID value assigned to the Extension when it is created.
     * A channel that recently went live may take a few minutes to appear in this list,
     * and a channel may continue to appear on this list for a few minutes after it stops broadcasting.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-live-channels
     *
     * @param array<string, mixed> $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     */
    public function getExtensionLiveChannels(array $parameters = [], Paginator $paginator = null): Result
    {
        $this->validateRequired($parameters, ['extension_id']);

        return $this->get('extensions/live', $parameters, $paginator);
    }

    /**
     * Retrieves a specified Extension’s secret data consisting of a version and an array of secret objects.
     * Each secret object contains a base64-encoded secret, a UTC timestamp when the secret becomes active,
     * and a timestamp when the secret expires.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-secrets
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getExtensionSecrets(array $parameters = []): Result
    {
        return $this->get('extensions/jwt/secrets', $parameters);
    }

    /**
     * Creates a JWT signing secret for a specific Extension. Also rotates any current secrets out of service,
     * with enough time for instances of the Extension to gracefully switch over to the new secret.
     * Use this function only when you are ready to install the new secret it returns.
     *
     * @see https://dev.twitch.tv/docs/api/reference#create-extension-secret
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function createExtensionSecret(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['extension_id']);

        return $this->post('extensions/jwt/secrets', $parameters);
    }

    /**
     * Sends a specified chat message to a specified channel.
     * The message will appear in the channel’s chat as a normal message.
     * The “username” of the message is the Extension name.
     *
     * There is a limit of 12 messages per minute, per channel.
     * Extension chat messages use the same rate-limiting functionality as the Twitch API (see Rate Limits).
     *
     * @see https://dev.twitch.tv/docs/api/reference#send-extension-chat-message
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function sendExtensionChatMessage(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($parameters, ['broadcaster_id']);
        $this->validateRequired($body, ['text', 'extension_id', 'extension_version']);

        return $this->post('extensions/chat', $parameters, null, $body);
    }

    /**
     * Gets information about your Extensions; either the current version or a specified version.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extensions
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getExtensions(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['extension_id']);

        return $this->get('extensions', $parameters);
    }

    /**
     * Gets information about a released Extension; either the current version or a specified version.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-released-extensions
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getReleasedExtensions(array $parameters = []): Result
    {
        $this->validateRequired($parameters, ['extension_id']);

        return $this->get('extensions/released', $parameters);
    }

    /**
     * Gets a list of Bits products that belongs to an Extension.
     *
     * @see https://dev.twitch.tv/docs/api/reference#get-extension-bits-products
     *
     * @param array<string, mixed> $parameters
     *
     * @return Result
     */
    public function getExtensionBitsProducts(array $parameters = []): Result
    {
        return $this->get('bits/extensions', $parameters);
    }

    /**
     * Add or update a Bits products that belongs to an Extension.
     *
     * @see https://dev.twitch.tv/docs/api/reference#update-extension-bits-product
     *
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $body
     *
     * @return Result
     */
    public function updateExtensionBitsProduct(array $parameters = [], array $body = []): Result
    {
        $this->validateRequired($body, ['sku', 'cost', 'display_name']);

        return $this->put('bits/extensions', $parameters, null, $body);
    }
}
