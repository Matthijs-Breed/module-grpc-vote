<?php

namespace Experius\GrpcVote\Api;

interface VotingClientInterface
{
    /**
     * @param string $url
     * @param \Experius\GrpcVote\Api\Vote $vote
     * @return string
     */
    public function vote(string $url, \Experius\GrpcVote\Api\Vote $vote): string;
}
