<?php

namespace LifeSpikes\PhpBeam\Support\SimpleHttpClient\Enums;

enum HttpMethod: string
{
    case POST   = 'POST';
    case GET    = 'GET';
    case PUT    = 'PUT';
    case PATCH  = 'PATCH';
    case DELETE = 'DELETE';
}
