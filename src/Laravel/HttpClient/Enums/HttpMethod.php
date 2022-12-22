<?php

namespace LifeSpikes\PhpBeam\Laravel\HttpClient\Enums;

enum HttpMethod: string
{
    case POST   = 'POST';
    case GET    = 'GET';
    case PUT    = 'PUT';
    case PATCH  = 'PATCH';
    case DELETE = 'DELETE';
}
