<?php

namespace Experius\GrpcVote\Service;

use Experius\GrpcVote\Api\Vote;
use Experius\GrpcVote\Api\VotingClientInterface;
use Voting\VotingRequest;
use Voting\VotingRequestFactory;
use Voting\VotingResponse;
use Grpc\BaseStub;
use Grpc\ChannelCredentials;
use Magento\Framework\Exception\LocalizedException;

class VotingClient extends BaseStub implements VotingClientInterface
{
    public function __construct(
        protected VotingRequestFactory $votingRequestFactory,
        string $hostname,
        array $opts = [],
        $channel = null
    ) {
        $opts['credentials'] = $opts['credentials'] ?? ChannelCredentials::createInsecure();
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param string $url
     * @param Vote $vote
     * @return string
     */
    public function vote(
        string $url,
        Vote $vote
    ):string {
        $vote = $this->votingRequestFactory->create([
            'url' => $url,
            'vote' => $vote->value
        ]);
        try {
            $result = $this->sendVote($vote);
        } catch (LocalizedException $e) {
            $result = $e->getMessage();
        }
        return $result->getConfirmation();
    }

    /**
     * @param VotingRequest $votingRequest
     * @param array $metadata
     * @param array $options
     * @return VotingResponse
     * @throws LocalizedException
     */
    public function sendVote(
        VotingRequest $votingRequest,
        array $metadata = [],
        array $options = []
    ): VotingResponse {
        $request = $this->_simpleRequest(
            '/voting.Voting/Vote',
            $votingRequest,
            [
                VotingResponse::class,
                'decode'
            ],
            $metadata,
            $options
        );
        /**
         * @var $response VotingResponse
         * @var $status \stdClass
         */
        [$response, $status] = $request->wait();

        if (!$response) {
            throw new LocalizedException(__('Vote service failure: %1', $status->code));
        }
        return $response;
    }
}
