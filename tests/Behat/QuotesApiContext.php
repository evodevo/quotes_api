<?php

declare(strict_types=1);

namespace QuotesAPI\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class QuotesContext
 * @package QuotesAPI\Tests\Behat
 */
class QuotesApiContext extends RawMinkContext implements Context
{
    /**
     * Checks if a response JSON collection (array) has value.
     *
     * @Then the response JSON collection should contain value :expectedValue
     * @param string $expectedValue
     * @throws \Exception
     */
    public function theResponseJsonCollectionShouldContainValue($expectedValue)
    {
        $response = $this->getResponseContentJson();
        if(!in_array($expectedValue, $response)) {
            throw new \Exception('Expected response json array to contain value ' . $expectedValue);
        }
        return;
    }

    /**
     * @return mixed
     */
    protected function getResponseContentJson()
    {
        return json_decode($this->getClient()->getResponse()->getContent());
    }

    /**
     * @return mixed
     */
    protected function getClient()
    {
        $driver = $this->getSession()->getDriver();

        return $driver->getClient();
    }
}