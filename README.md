# AwsBedrock

A Laravel module for aws-bedrock.

[![tests](https://github.com/descom-es/aws-bedrock/actions/workflows/tests.yml/badge.svg)](https://github.com/descom-es/aws-bedrock/actions/workflows/tests.yml)
[![static analysis](https://github.com/descom-es/aws-bedrock/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/descom-es/aws-bedrock/actions/workflows/static-analysis.yml)
[![lint](https://github.com/descom-es/aws-bedrock/actions/workflows/lint.yml/badge.svg)](https://github.com/descom-es/aws-bedrock/actions/workflows/lint.yml)

## Usage

`BedrockClientConverse` is the wrapper around AWS Bedrock's Converse API. The recommended way to use it is through the `Agent` abstraction; you can also instantiate the client directly when you need finer control over the message history.

### 1. Recommended flow — extend `Agent`

Define an agent by extending `Descom\AwsBedrock\Converse\Agent` and providing a model id, a system prompt and the list of tools (empty array if you don't use any):

```php
use Descom\AwsBedrock\Converse\Agent;

final class SupportAgent extends Agent
{
    protected string $modelId = 'anthropic.claude-3-haiku-20240307-v1:0';

    public function systemPrompt(): ?string
    {
        return 'You are a customer-support assistant.';
    }

    public function tools(): array
    {
        return [];
    }
}
```

`Agent` exposes three convenience helpers that build the `Messages` collection for you:

```php
$agent = new SupportAgent();

$response = $agent->ask('How do I reset my password?');
$response = $agent->sendImage('/path/to/screenshot.png', 'What error is shown?');
$response = $agent->sendDocument('/path/to/invoice.pdf', 'Summarize this invoice.');

echo $response->output()->message->text();
echo $response->usage()->totalTokens;
echo $response->metrics()->latencyMs;
```

### 2. Direct use of `BedrockClientConverse`

When you need to build a custom message history (multi-turn conversation, mixed roles, replayed transcripts) instantiate the client directly with an `Agent` and call `request()`:

```php
use Descom\AwsBedrock\Converse\BedrockClientConverse;
use Descom\AwsBedrock\Converse\Messages\Contents\Contents;
use Descom\AwsBedrock\Converse\Messages\Contents\TextContent;
use Descom\AwsBedrock\Converse\Messages\Message;
use Descom\AwsBedrock\Converse\Messages\Messages;
use Descom\AwsBedrock\Converse\Messages\Role;

$client = new BedrockClientConverse(new SupportAgent());

$messages = new Messages([
    new Message(Role::USER, new Contents([new TextContent('Hi!')])),
    new Message(Role::ASSISTANT, new Contents([new TextContent('Hello, how can I help?')])),
    new Message(Role::USER, new Contents([new TextContent('I forgot my password.')])),
]);

$response = $client->request($messages);
```

### 3. Multi-modal message (image + text in a single turn)

A single message can carry several content items. Combine `TextContent` with `ImageContent`, `DocumentContent` or `AudioContent`, and choose `BinarySource` (raw bytes) or `S3Source` (objects stored in S3):

```php
use Descom\AwsBedrock\Converse\Messages\Contents\ImageContent;
use Descom\AwsBedrock\Converse\Messages\Contents\Sources\BinarySource;

$contents = new Contents([
    new TextContent('What is in this picture?'),
    new ImageContent('png', new BinarySource(file_get_contents('photo.png'))),
]);

$messages = new Messages([new Message(Role::USER, $contents)]);

$response = $client->request($messages);
```

### 4. Tool calling

Define a tool by implementing `Descom\AwsBedrock\Tools\ToolsContract`:

```php
use Descom\AwsBedrock\Tools\ToolsContract;

final class GetWeatherTool implements ToolsContract
{
    public function __construct(public object|array $arguments) {}

    public function run(object|array $parameters): bool|string|array
    {
        return "Sunny, 25C in {$parameters['location']}";
    }
}
```

Then expose it from your agent's `tools()` method. Each entry describes the JSON schema sent to Bedrock and the PHP class that handles invocations:

```php
public function tools(): array
{
    return [[
        'name' => 'get_weather',
        'description' => 'Get current weather for a location',
        'parameters' => [
            'type' => 'object',
            'properties' => [
                'location' => ['type' => 'string', 'description' => 'City name'],
            ],
            'required' => ['location'],
        ],
        'tool_action' => [
            'class' => GetWeatherTool::class,
            'arguments' => [],
        ],
    ]];
}
```

When the model returns a `tool_use` stop reason, `BedrockClientConverse::request()` automatically runs the tool, appends its result to the conversation and re-calls the model — your code only needs to read the final `Response`.

### 5. Inspecting the response

`request()` returns a `Descom\AwsBedrock\Converse\Messages\Response\Response` with typed accessors:

```php
$response->output()->message->text();      // assistant text
$response->output()->message->toolUses();  // ToolUseContent[]
$response->stopReason()->value;            // 'end_turn', 'tool_use', ...
$response->usage()->inputTokens;
$response->usage()->outputTokens;
$response->usage()->totalTokens;
$response->metrics()->latencyMs;
```

### 6. Error handling

Any AWS SDK exception raised during the underlying `converse()` call is wrapped in `BedrockRequestException`. The original exception is preserved as `$previous`, and the offending request payload is exposed on the `payload` property for debugging:

```php
use Descom\AwsBedrock\Converse\Exceptions\BedrockRequestException;

try {
    $response = $agent->ask('Hello');
} catch (BedrockRequestException $e) {
    Log::error('Bedrock call failed', [
        'message' => $e->getMessage(),
        'payload' => $e->payload,
    ]);
    throw $e;
}
```
