# Laravel Instantly
Simple Laravel API wrapper for [Instantly.ai](https://instantly.ai/) email marketing tool.

## Installation

You can install the package via composer:
```bash
composer require martin-ro/laravel-instantly
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="MartinRo\Instantly\InstantlyServiceProvider"
```
This is the contents of the published config file:
```bash
return [
    'api_key' => env('INSTANTLY_API_KEY'),
];
```

## Usage

Initialize the client:
```php
use MartinRo\Instantly\InstantlyClient;

$client = new InstantlyClient();
```

Available methods:
[See Instantly docs](https://developer.instantly.ai/campaign).
```php
$client->getWorkspace();

$client->listCampaigns();

$client->getCampaignName(string $campaignId);

$client->setCampaignName(string $campaignId, $newName);

$client->getCampaignStatus(string $campaignId);

$client->getCampaignAccounts(string $campaignId);

$client->setCampaignAccounts(string $campaignId, array $accounts);

$client->addCampaignAccount(string $campaignId, string $account);

$client->removeCampaignAccount(string $campaignId, string $account);

$client->launchCampaign(string $campaignId);

$client->pauseCampaign(string $campaignId);

$client->getCampaignSummary(string $campaignId);

$client->getCampaignCount(Carbon $start, Carbon $end);

$client->addLeadsToCampaign(string $campaignId, array $leads, bool $skipIfInWorkspace = true);

$client->getLead(string $email, string $campaignId = null);

$client->deleteLead(array $deleteList, bool $deleteAllFromCompany = false, string $campaignId = null);

$client->updateLeadStatus(string $campaignId, string $email, string $newStatus);

$client->setLeadVariable(string $campaignId, string $email, array $variables);

$client->updateLeadVariable(string $campaignId, string $email, array $variables);

$client->deleteLeadVariable(string $campaignId, string $email, array $variables);

$client->addToBlocklist(array $entries);

$client->deleteFromBlocklist(array $entries);

$client->listAccounts(int $limit = 10, int $skip = 0);

$client->checkAccountVitals(array $accounts);

$client->getAccountStatus(string $email);

$client->enableAccountWarmup(string $email);

$client->pauseAccountWarmup(string $email);

$client->markAccountsFixed(string $email = '');

$client->deleteAccount(string $email);
```