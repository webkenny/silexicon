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

use PhpSpec\ObjectBehavior;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends ObjectBehavior implements Context, CustomSnippetAcceptingContext
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
     * @todo Send parameters to this constructor once we learn how to Behat. :)
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
        catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

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
        if((int) $response->getStatusCode() !== (int) $statusCode) {
            throw new Exception("You expected $statusCode but received " . $response->getStatusCode());
        }
    }

    /**
     * @Then /^scope into the "([^"]*)" property$/
     */
    public function scopeIntoTheProperty($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @Then /^the properties exist:$/
     */
    public function thePropertiesExist(PyStringNode $propertiesString)
    {
        foreach (explode("\n", (string) $propertiesString) as $property) {
            $this->thePropertyExists($property);
        }
    }

    /**
     * @Given /^the "([^"]*)" property exists$/
     */
    public function thePropertyExists($property)
    {
        $payload = $this->getScopePayload();
        $message = sprintf(
          'Asserting the [%s] property exists in the scope [%s]: %s',
          $property,
          $this->scope,
          json_encode($payload)
        );

        if (is_object($payload)) {

        }

    }

    /**
     * @Then /^the "([^"]*)" property is a string$/
     */
    public function thePropertyIsAString($property)
    {
        if(! is_string($property)){
            throw new Exception("The property named $property is not a string");
        }
    }

    /**
     * Checks the response exists and returns it.
     *
     * @return  GuzzleHttp\Message\Response
     * @throws  Exception
     */
    protected function getResponse()
    {
        if (!$this->response) {
            throw new Exception("You must first make a request to check a response.");
        }
        return $this->response;
    }


    /**
     * Return the response payload from the current response.
     *
     * @return  mixed
     * @throws  mixed
     */
    protected function getResponsePayload()
    {
        if (! $this->responsePayload) {
            $json = json_decode($this->getResponse()->getBody(true));

            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Failed to decode JSON body ';

                switch (json_last_error()) {
                    case JSON_ERROR_DEPTH:
                        $message .= '(Maximum stack depth exceeded).';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $message .= '(Underflow or the modes mismatch).';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $message .= '(Unexpected control character found).';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $message .= '(Syntax error, malformed JSON).';
                        break;
                    case JSON_ERROR_UTF8:
                        $message .= '(Malformed UTF-8 characters, possibly incorrectly encoded).';
                        break;
                    default:
                        $message .= '(Unknown error).';
                        break;
                }

                throw new Exception($message);
            }

            $this->responsePayload = $json;
        }

        return $this->responsePayload;
    }

    /**
     * Returns the payload from the current scope within
     * the response.
     *
     * @return mixed
     */
    protected function getScopePayload()
    {
        $payload = $this->getResponsePayload();

        if (! $this->scope) {
            return $payload;
        }

        return $this->arrayGet($payload, $this->scope);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @copyright   Taylor Otwell
     * @link        http://laravel.com/docs/helpers
     * @param       array   $array
     * @param       string  $key
     * @return      mixed
     */
    protected function arrayGet($array, $key)
    {
        if (is_null($key)) {
            return $array;
        }

        // if (isset($array[$key])) {
        //     return $array[$key];
        // }

        foreach (explode('.', $key) as $segment) {

            if (is_object($array)) {
                if (! isset($array->{$segment})) {
                    return;
                }
                $array = $array->{$segment};

            } elseif (is_array($array)) {
                if (! array_key_exists($segment, $array)) {
                    return;
                }
                $array = $array[$segment];
            }
        }

        return $array;
    }
}
