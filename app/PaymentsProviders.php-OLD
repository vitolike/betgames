<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsProviders extends Model
{
    protected $table = 'payments_providers';

    protected $fillable = [
        'payment_methods',
        'provider_name',
        'url_return_ipn',
        'provider_status',
        'provider_mode',
        'provider_key',
        'provider_token',
        'provider_key_dev',
        'provider_token_dev',
        'bearer_token'
    ];

    protected $hidden = ['created_at'];

    /**
     * Get credentials of payment provider in current mode.
     * > 0 = Development | 1 = Production
     *
     * @param $providerName
     * @return array
     */
    public function getClientCredentials($providerName)
    {
        $provider = self::select('provider_mode', 'provider_key', 'provider_token', 'provider_key_dev', 'provider_token_dev', 'url_return_ipn', 'bearer_token')->where('provider_name', $providerName)->first();

        if ($provider->provider_mode === 0)
        {
            // 0 = Development
            $key = $provider->provider_key_dev;
            $token = $provider->provider_token_dev;
        }
        else if ($provider->provider_mode === 1)
        {
            // 1 = Production
            $key = $provider->provider_key;
            $token = $provider->provider_token;
        }

        $url = $provider->url_return_ipn;
        $bearer_token = $provider->bearer_token;

        return array(
            'client_key' => $key,
            'client_token' => $token,
            'url_return_ipn' => $url,
            'bearer_token' => $bearer_token
        );
    }

    public function getBearerToken_Paggue($providerName, $token): array
    {
        $provider = self::select('bearer_token')->where('provider_name', $providerName)->first();

        $this->where('provider_name', $providerName)
            ->update([
                'bearer_token' => $token
            ]);

        return array(
            'bearer_token' => $provider
        );
    }
}
