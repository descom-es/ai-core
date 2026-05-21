<?php

namespace Descom\Ai\Bedrock\Converse;

use Aws\BedrockRuntime\BedrockRuntimeClient;
use Aws\Credentials\CredentialProvider;
use Descom\Ai\Bedrock\Converse\Exceptions\BedrockRequestException;
use Descom\Ai\Bedrock\Messages\Contents\Contents;
use Descom\Ai\Bedrock\Messages\Message;
use Descom\Ai\Bedrock\Messages\Messages;
use Descom\Ai\Bedrock\Messages\Response\Output\ToolUseContent;
use Descom\Ai\Bedrock\Messages\Response\Response;
use Descom\Ai\Bedrock\Messages\Role;
use Descom\Ai\Tools\ExecuteTool;
use Illuminate\Support\Facades\App;

class BedrockClientConverse
{
    public function __construct(
        public readonly Agent $agent
    ) {}

    public function tools(): array
    {
        $tools = array_map(fn (array $tool) => [
            'toolSpec' => [
                'name' => $tool['name'],
                'description' => $tool['description'],
                'inputSchema' => [
                    'json' => $tool['parameters'],
                ],
            ],
        ], $this->agent->tools());

        return [
            'toolConfig' => [
                'tools' => $tools,
            ],
        ];
    }

    /**
     * @throws BedrockRequestException
     */
    public function request(Messages $messages): Response
    {
        $client = $this->client();
        $payload = $this->payload($messages);

        try {
            $response = $client->converse($payload);

            $result = new Response($response);

            return $this->workWithResponse($messages, $result);
        } catch (\Exception $exception) {
            throw new BedrockRequestException($payload, $exception);
        }
    }

    private function workWithResponse(Messages $messages, Response $response): Response
    {
        if ($response->stopReasonByToolUse()) {
            $toolUses = $response->output()->message->toolUses();

            $messages = $this->conversationIncrement($messages, Role::ASSISTANT, $toolUses);

            $contentsFromTools = $this->getResponseFromTools($toolUses);

            $messages = $this->conversationIncrement($messages, Role::USER, $contentsFromTools);

            return $this->request($messages);
        }

        return $response;
    }

    private function conversationIncrement(Messages $messages, Role $role, array $contents): Messages
    {
        $message = new Message($role, new Contents($contents));

        $messages->addMessage($message);

        return $messages;
    }

    private function getResponseFromTools(array $toolUses): array
    {
        return array_map(fn (ToolUseContent $toolUse) => $this->getResponseFromTool($toolUse), $toolUses);
    }

    private function getResponseFromTool(ToolUseContent $tool): array
    {
        $action = new ExecuteTool(
            name: $tool->name,
            tools: $this->agent->tools(),
        );

        return [
            'toolResult' => [
                'toolUseId' => $tool->toolUseId,
                'content' => [
                    [
                        'text' => $action->execute($tool->input),
                    ],
                ],
                'status' => 'success',
            ],
        ];
    }

    private function payload(Messages $messages): array
    {
        $payload = array_merge($this->payloadHeader(), $this->payloadBody($messages));

        return $payload;
    }

    private function payloadBody(Messages $messages): array
    {
        $payloadBody = [
            'system' => [[
                'text' => $this->agent->systemPrompt(),
            ]],
            'messages' => array_map(fn (Message $message) => $message->toArray(), $messages->all()),
        ];

        if ($this->agent->tools()) {
            $payloadBody = array_merge($payloadBody, $this->tools());
        }

        return $payloadBody;
    }

    private function payloadHeader(): array
    {
        return [
            'modelId' => $this->agent->modelId(),
        ];
    }

    private function client(): BedrockRuntimeClient
    {
        $config = match (App::environment()) {
            'local' => $this->configLocal(),
            default => config('aws.auth'),
        };

        return new BedrockRuntimeClient($config);
    }

    private function configLocal(): array
    {
        $credentials = CredentialProvider::sso('bedrock');

        return [
            'region' => config('aws.auth.region'),
            'version' => config('aws.auth.version'),
            'credentials' => $credentials,
        ];
    }
}
