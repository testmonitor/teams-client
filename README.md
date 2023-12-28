# TestMonitor Teams Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/teams-client/v/stable)](https://packagist.org/packages/testmonitor/teams-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/teams-client.svg)](https://circleci.com/gh/testmonitor/teams-client)
[![Travis Build](https://travis-ci.com/testmonitor/teams-client.svg?branch=main)](https://app.travis-ci.com/github/testmonitor/teams-client)
[![Code Coverage](https://scrutinizer-ci.com/g/testmonitor/teams-client/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/testmonitor/teams-client/?branch=main)
[![Code Quality](https://scrutinizer-ci.com/g/testmonitor/teams-client/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/testmonitor/teams-client/?branch=main)
[![StyleCI](https://styleci.io/repos/406275668/shield)](https://styleci.io/repos/406275668)
[![License](https://poser.pugx.org/testmonitor/teams-client/license)](https://packagist.org/packages/testmonitor/teams-client)

This package provides a very basic, convenient, and unified wrapper for sending messages to Microsoft Teams using an incoming webhook.

It's mostly a based on Sebastian Bretschneider's [PHP Microsoft Teams Connector](https://github.com/sebbmeyer/php-microsoft-teams-connector), but uses Guzzle
instead of the PHP CURL extension. This package exposes the excellent Card objects from PHP Microsoft Teams Connector to build all kinds of
messages using a fluent PHP syntax.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

To install the client you need to require the package using composer:

	$ composer require testmonitor/teams-client

Use composer's autoload:

```php
require __DIR__.'/../vendor/autoload.php';
```

You're all set up now!

## Usage

Before you can post messages, you need to set up an incoming webhook in Teams:

- Launch the **Microsoft Teams** application.
- Select the **Teams** tab.
- Select a team.
- Right-click on the channel you want the messages to be delivered and select **Connectors**.
- Select the **"Incoming Webhook"** connector and click Add.
- Provide your webhook with a name and optionally, a logo.
- Click **Create** and your webhook URL will be provided.

Use the webhook URL to create a new client instance:

```php
$teams = new \TestMonitor\Teams\Client('https://webhook.url/');
```

## Examples

Post a simple message to Teams:

```php
$card = new \TestMonitor\Teams\Resources\SimpleCard([
    'title' => 'Some title',
    'text' => 'Hello World!',
]);

$teams->postMessage($card);
```

The built-in connector package allows way more comprehensive messages. Here's another example:

```php
$user = (object) ['name' => 'John Doe'];

$card = new \TestMonitor\Teams\Resources\CustomCard('New Issue', "{$user->name} created a new issue");

$card->setColor('7FB11B')
    ->addFacts('Issue **I365**', [
        'Status' => '**Open**',
        'Priority' => '**High**',
        'Resolution' => '**Unresolved**',
    ])
    ->addAction('Open in TestMonitor', 'https://www.testmonitor.com/');

$teams->postMessage($card);
```

For more information on composing these messages, head over to
[PHP Microsoft Teams Connector](https://github.com/sebbmeyer/php-microsoft-teams-connector)
for more examples or refer to Microsoft's [Build cards and task modules documentation](https://docs.microsoft.com/en-us/microsoftteams/platform/task-modules-and-cards/cards-and-task-modules).

## Tests

The package contains integration tests. You can run them using PHPUnit.

    $ vendor/bin/phpunit

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

* **Thijs Kok** - *Lead developer* - [ThijsKok](https://github.com/thijskok)
* **Stephan Grootveld** - *Developer* - [Stefanius](https://github.com/stefanius)
* **Frank Keulen** - *Developer* - [FrankIsGek](https://github.com/frankisgek)
* **Muriel Nooder** - *Developer* - [ThaNoodle](https://github.com/thanoodle)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
