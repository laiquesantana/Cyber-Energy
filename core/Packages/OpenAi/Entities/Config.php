<?php

declare(strict_types=1);

namespace Saas\Project\Packages\OpenAi\Entities;

class Config
{
    private string $sapCompanyDb;
    private string $sapUsername;
    private string $sapPassword;
    private string $sapApiHost;

    /**
     * @return string
     */
    public function getSapCompanyDb(): string
    {
        return $this->sapCompanyDb;
    }

    /**
     * @return string
     */
    public function getSapUsername(): string
    {
        return $this->sapUsername;
    }

    /**
     * @return string
     */
    public function getSapPassword(): string
    {
        return $this->sapPassword;
    }

    /**
     * @return string
     */
    public function getSapApiHost(): string
    {
        return $this->sapApiHost;
    }

    /**
     * @param string $sapCompanyDb
     * @param string $sapUsername
     * @param string $sapPassword
     * @param string $sapApiHost
     */
    public function __construct(string $sapCompanyDb, string $sapUsername, string $sapPassword, string $sapApiHost)
    {
        $this->sapCompanyDb = $sapCompanyDb;
        $this->sapUsername = $sapUsername;
        $this->sapPassword = $sapPassword;
        $this->sapApiHost = $sapApiHost;
    }
}
