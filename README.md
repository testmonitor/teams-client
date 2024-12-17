# TestMonitor Teams Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/teams-client/v/stable)](https://packagist.org/packages/testmonitor/teams-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/teams-client.svg)](https://circleci.com/gh/testmonitor/teams-client)
[![StyleCI](https://styleci.io/repos/406275668/shield)](https://styleci.io/repos/406275668)
[![codecov](https://codecov.io/gh/testmonitor/teams-client/graph/badge.svg?token=OIKZ7XZMPI)](https://codecov.io/gh/testmonitor/teams-client)
[![License](https://poser.pugx.org/testmonitor/teams-client/license)](https://packagist.org/packages/testmonitor/teams-client)

This package provides a very basic, convenient, and unified wrapper for sending messages to Microsoft Teams using an incoming webhook.

It's mostly a based on Sebastian Bretschneider's [PHP Microsoft Teams Connector](https://github.com/sebbmeyer/php-microsoft-teams-connector), but uses Guzzle
instead of the PHP CURL extension. This package leverages Microsoft Teams Adaptive Cards, making it a great fit for Power Automate.

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
- Locate the channel where you want notifications delivered, then click the three dots (More options) next to it.
- Select **Workflows** from the dropdown menu.
- In the search bar, type "webhook".
- Choose the **Post to a channel when a webhook request is received** template.
- Enter a name for the workflow or use the default name.
- Click **Next**.
- Confirm the selected **Team** and **Channel**.
- Click **Add workflow** and your webhook URL will be provided.

Use the webhook URL to create a new client instance:

```php
$teams = new \TestMonitor\Teams\Client('https://webhook.url/');
```

## Examples

Post a simple message to Teams:

```php
$card = new \TestMonitor\Teams\Resources\Card;

$card->addElement(
    new \TestMonitor\Teams\Resources\Card\Elements\TextBlock('Simple heading')
);

$teams->postMessage($card);
```

Adaptive cards allow way more comprehensive messages. Here's another example:

```php
$card = new \TestMonitor\Teams\Resources\Card;

$title = new \TestMonitor\Teams\Resources\Card\Elements\TextBlock('Simple heading');
$facts = new \TestMonitor\Teams\Resources\Card\Elements\FactSet(
    new \TestMonitor\Teams\Resources\Card\Elements\Fact('Status', 'Completed'),
    new \TestMonitor\Teams\Resources\Card\Elements\Fact('Category', 'Feature request'),
);
$action = new \TestMonitor\Teams\Resources\Card\Actions\OpenUrl('https://www.testmonitor.com/');

$card->addElement($title)
     ->addElement($facts)
     ->addAction($action);

$teams->postMessage($card);
```

For more information on composing these messages, head over to
[PHP Microsoft Teams Connector](https://github.com/sebbmeyer/php-microsoft-teams-connector)
for more examples or refer to Microsoft's documentation on [Adaptive Cards](https://adaptivecards.io/).

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
