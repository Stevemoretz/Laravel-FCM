<?php

namespace LaravelFCM\Sender;

use LaravelFCM\Request\TopicRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FCMGroup.
 */
class FCMTopic extends HTTPSender {

    private $add_subscription_url = 'https://iid.googleapis.com/iid/v1:batchAdd';
    private $remove_subscription_url = 'https://iid.googleapis.com/iid/v1:batchRemove';

    /**
     * Create a topic.
     *
     * @param string $topicId
     * @param string $registrationId
     *
     * @return bool
     */
    public function createTopic($topicId, $registrationId)
    {
        $request = new TopicRequest('create', $topicId);
        if (is_array($registrationId)) {
            return null;
        }
        $response = $this->client->request('post', $this->url . $registrationId . '/rel/topics/' . $topicId, $request->build());


        if ($this->isValidResponse($response)) {
            return true;
        }
        return false;

    }

    /**
     * Add subscription to a topic.
     *
     * @param string $topicId
     * @param array|string $recipientsTokens
     * @return bool
     */
    public function subscribeTopic($topicId, $recipientsTokens)
    {
        $request = new TopicRequest('subscribe', $topicId, $recipientsTokens);
        $response = $this->client->request('post', $this->add_subscription_url, $request->build());

        if ($this->isValidResponse($response)) {
            return true;
        }
        return false;
    }

    /**
     * Remove subscription from a topic.
     *
     *
     * @param string $topicId
     * @param array|string $recipientsTokens
     * @return bool
     */
    public function unsubscribeTopic($topicId, $recipientsTokens)
    {
        $request = new TopicRequest('unsubscribe', $topicId, $recipientsTokens);
        $response = $this->client->request('post', $this->remove_subscription_url, $request->build());

        if ($this->isValidResponse($response)) {
            return true;
        }
        return false;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return bool
     */
    public function isValidResponse(ResponseInterface $response)
    {
        return $response->getStatusCode() === 200;
    }
}
