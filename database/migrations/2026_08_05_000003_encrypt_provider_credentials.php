<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->transformCredentials(function (string $value): string {
            try {
                Crypt::decryptString($value);

                return $value;
            } catch (DecryptException) {
                return Crypt::encryptString($value);
            }
        });
    }

    public function down(): void
    {
        $this->transformCredentials(function (string $value): string {
            try {
                return Crypt::decryptString($value);
            } catch (DecryptException) {
                return $value;
            }
        });
    }

    private function transformCredentials(callable $transform): void
    {
        foreach (['communication_providers', 'courier_providers'] as $table) {
            DB::table($table)
                ->select(['id', 'api_key', 'api_secret'])
                ->orderBy('id')
                ->chunkById(100, function ($providers) use ($table, $transform): void {
                    foreach ($providers as $provider) {
                        DB::table($table)
                            ->where('id', $provider->id)
                            ->update([
                                'api_key' => $provider->api_key === null
                                    ? null
                                    : $transform($provider->api_key),
                                'api_secret' => $provider->api_secret === null
                                    ? null
                                    : $transform($provider->api_secret),
                            ]);
                    }
                });
        }
    }
};
