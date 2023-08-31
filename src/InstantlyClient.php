<?php

namespace MartinRo\Instantly;

use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use MartinRo\Instantly\Exceptions\NoApiKeyProvided;
use Throwable;

class InstantlyClient
{
    private PendingRequest $httpClient;

    private string $baseUrl = 'https://api.instantly.ai/api/v1/';

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $apiKey = config('instantly.api_key');

        throw_unless($apiKey, new NoApiKeyProvided());

        $this->httpClient = Http::withHeaders(['Content-Type' => 'application/json'])
            ->baseUrl($this->baseUrl);
    }

    public function getWorkspace()
    {
        return $this->httpClient->get(
            'authenticate', $this->buildQuery()
        )->json();
    }

    public function listCampaigns()
    {
        return $this->httpClient->get(
            'campaign/list', $this->buildQuery()
        )->json();
    }

    public function getCampaignName(string $campaignId)
    {
        return $this->httpClient->get(
            'campaign/get/name', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function setCampaignName(string $campaignId, $newName)
    {
        return $this->httpClient->post(
            'campaign/set/name', $this->buildQuery([
                'campaign_id' => $campaignId,
                'name' => $newName,
            ]),
        )->json();
    }

    public function getCampaignStatus(string $campaignId)
    {
        return $this->httpClient->get(
            'campaign/get/status', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function getCampaignAccounts(string $campaignId)
    {
        return $this->httpClient->get(
            'campaign/get/accounts', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function setCampaignAccounts(string $campaignId, array $accounts)
    {
        return $this->httpClient->post(
            'campaign/set/accounts', $this->buildQuery([
                'campaign_id' => $campaignId,
                'account_list' => $accounts,
            ]),
        )->json();
    }

    public function addCampaignAccount(string $campaignId, string $account)
    {
        return $this->httpClient->post(
            'campaign/add/account', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $account,
            ]),
        )->json();
    }

    public function removeCampaignAccount(string $campaignId, string $account)
    {
        return $this->httpClient->post(
            'campaign/remove/account', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $account,
            ]),
        )->json();
    }

    public function launchCampaign(string $campaignId)
    {
        return $this->httpClient->post(
            'campaign/launch', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function pauseCampaign(string $campaignId)
    {
        return $this->httpClient->post(
            'campaign/pause', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function getCampaignSummary(string $campaignId)
    {
        return $this->httpClient->get(
            'analytics/campaign/summary', $this->buildQuery([
                'campaign_id' => $campaignId,
            ]),
        )->json();
    }

    public function getCampaignCount(Carbon $start, Carbon $end)
    {
        return $this->httpClient->get(
            'analytics/campaign/count', $this->buildQuery([
                'start_date' => $start->format('m-d-Y'),
                'end_date' => $end->format('m-d-Y'),
            ]),
        )->json();
    }

    public function addLeadsToCampaign(string $campaignId, array $leads, bool $skipIfInWorkspace = true)
    {
        return $this->httpClient->post(
            'lead/add', $this->buildQuery([
                'campaign_id' => $campaignId,
                'leads' => $leads,
                'skip_if_in_workspace' => $skipIfInWorkspace,
            ]),
        )->json();
    }

    public function getLead(string $email, string $campaignId = null)
    {
        return $this->httpClient->get(
            'lead/get', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $email,
            ]),
        )->json();
    }

    public function deleteLead(array $deleteList, bool $deleteAllFromCompany = false, string $campaignId = null)
    {
        return $this->httpClient->post(
            'lead/delete', $this->buildQuery([
                'campaign_id' => $campaignId,
                'delete_list' => $deleteList,
                'delete_all_from_company' => $deleteAllFromCompany,
            ]),
        )->json();
    }

    public function updateLeadStatus(string $campaignId, string $email, string $newStatus)
    {
        return $this->httpClient->post(
            'lead/update/status', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $email,
                'new_status' => $newStatus,
            ]),
        )->json();
    }

    public function setLeadVariable(string $campaignId, string $email, array $variables)
    {
        return $this->httpClient->post(
            'lead/data/set', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $email,
                'variables' => $variables,
            ]),
        )->json();
    }

    public function updateLeadVariable(string $campaignId, string $email, array $variables)
    {
        return $this->httpClient->post(
            'lead/data/update', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $email,
                'variables' => $variables,
            ]),
        )->json();
    }

    public function deleteLeadVariable(string $campaignId, string $email, array $variables)
    {
        return $this->httpClient->post(
            'lead/data/delete', $this->buildQuery([
                'campaign_id' => $campaignId,
                'email' => $email,
                'variables' => $variables,
            ]),
        )->json();
    }

    public function addToBlocklist(array $entries)
    {
        return $this->httpClient->post(
            'blocklist/add/entries', $this->buildQuery([
                'entries' => $entries,
            ]),
        )->json();
    }

    public function deleteFromBlocklist(array $entries)
    {
        return $this->httpClient->post(
            'blocklist/delete/entries', $this->buildQuery([
                'entries' => $entries,
            ]),
        )->json();
    }

    public function listAccounts(int $limit = 10, int $skip = 0)
    {
        return $this->httpClient->get(
            'account/list', $this->buildQuery([
                'limit' => $limit,
                'skip' => $skip,
            ]),
        )->json();
    }

    public function checkAccountVitals(array $accounts)
    {
        return $this->httpClient->post(
            'account/test/vitals', $this->buildQuery([
                'accounts' => $accounts,
            ]),
        )->json();
    }

    public function getAccountStatus(string $email)
    {
        return $this->httpClient->get(
            'account/status', $this->buildQuery([
                'email' => $email,
            ]),
        )->json();
    }

    public function enableAccountWarmup(string $email)
    {
        return $this->httpClient->post(
            'account/warmup/enable', $this->buildQuery([
                'email' => $email,
            ]),
        )->json();
    }

    public function pauseAccountWarmup(string $email)
    {
        return $this->httpClient->post(
            'account/warmup/pause', $this->buildQuery([
                'email' => $email,
            ]),
        )->json();
    }

    public function markAccountsFixed(string $email = '')
    {
        return $this->httpClient->post(
            'account/mark_fixed', $this->buildQuery([
                'email' => $email,
            ]),
        )->json();
    }

    public function deleteAccount(string $email)
    {
        return $this->httpClient->post(
            'account/delete', $this->buildQuery([
                'email' => $email,
            ]),
        )->json();
    }

    public function buildQuery(array $options = []): array
    {
        return array_merge(
            $options,
            ['api_key' => config('instantly.api_key')],
        );
    }
}
