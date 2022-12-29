<?php

namespace Experius\GrpcVote\Api;

enum Vote: int
{
    case UP     = 0;
    case DOWN   = 1;
}
