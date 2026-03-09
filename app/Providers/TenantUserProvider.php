<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;

class TenantUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        if (app()->bound('tenant')) {
            $user = $model->setConnection('mongodb')
                ->newQuery()
                ->where($model->getAuthIdentifierName(), $identifier)
                ->first();

            if ($user) {
                return $user;
            }
        }

        return $model->setConnection('mongodb_central')
            ->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        if (app()->bound('tenant')) {
            $user = $model->setConnection('mongodb')
                 ->newQuery()
                 ->where($model->getAuthIdentifierName(), $identifier)
                 ->where($model->getRememberTokenName(), $token)
                 ->first();
                 
            if ($user) {
                return $user;
            }
        }

        return $model->setConnection('mongodb_central')
             ->newQuery()
             ->where($model->getAuthIdentifierName(), $identifier)
             ->where($model->getRememberTokenName(), $token)
             ->first();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return null;
        }

        if (app()->bound('tenant')) {
            $model = $this->createModel();
            $query = $model->setConnection('mongodb')->newQuery();

            foreach ($credentials as $key => $value) {
                if (is_array($value) || $value instanceof \Closure) {
                    $query->where($key, $value);
                } else {
                    $query->where($key, $value);
                }
            }

            $user = $query->first();
            if ($user) {
                return $user;
            }
        }

        $model = $this->createModel();
        $query = $model->setConnection('mongodb_central')->newQuery();

        foreach ($credentials as $key => $value) {
            if (is_array($value) || $value instanceof \Closure) {
                $query->where($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}
