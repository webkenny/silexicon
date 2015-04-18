<?php

require '../../vendor/autoload.php';

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Context\CustomSnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, CustomSnippetAcceptingContext
{
    /**
     * Guzzle HTTP Client
     */
    protected $client;

    /**
     * The current resource
     */
    protected $resource;

    /**
     * The request payload;
     */
    protected $requestPayload;

    /**
     * The response from Guzzle
     */
    protected $response;

    /**
     * The decoded response object.
     */
    protected $responsePayload;

    /**
     * The current scope within the response payload
     * which conditions are asserted against.
     */
    protected $scope;


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     * @param array $parameters context parameters
     */
    public function __construct()
    {
        $this->client = new Client(['base_url' => "http://silex.localhost:8888"]);
    }

    public static function getAcceptedSnippetType()
    {
        return 'regex';
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $resource)
    {
        $this->resource = $resource;
        $method = strtolower($httpMethod);

        try{
            switch($httpMethod) {
                case 'GET':
                    $this->response = $this->client->$method($resource);
                    break;
                default:
                    $this->response = $this->client->$method($resource);
            }
        }
        catch (BadResponseException $e) {
            $response = $e->getReponse();

            // Sometimes the request will fail, at which point we have
            // no response at all. Let Guzzle give an error here, it's
            // pretty self-explanatory.
            if ($response === null) {
                throw $e;
            }

            $this->response = $e->getResponse();
        }
    }

    /**
     * @Then /^I get a "([^"]*)" response$/
     */
    public function iGetAResponse($statusCode)
    {
        $response = $this->getResponse();
        $contentType = $response->getHeader('Content-Type');

        if ($contentType === 'application/json') {
            $bodyOutput = $response->getBody();
        } else {
            $bodyOutput = 'Output is '.$contentType.', which is not JSON and is therefore scary. Run the request manually.';
        }
        assertSame((int) $statusCode, (int) $this->getResponse()->getStatusCode(), $bodyOutput);
    }

    /**
     * @Then /^scope into the "([^"]*)" property$/
     */
    public function scopeIntoTheProperty($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the properties exist:$/
     */
    public function thePropertiesExist(PyStringNode $string)
    {
        throw new PendingException();
    }

    /**
     * @Then /^the "([^"]*)" property is a string$/
     */
    public function thePropertyIsAString($arg1)
    {
        throw new PendingException();
    }
}
