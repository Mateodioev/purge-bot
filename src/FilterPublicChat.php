<?php

namespace App;

use Attribute;
use Mateodioev\TgHandler\Context;
use Mateodioev\TgHandler\Filters\Filter;

/**
 * This filter validates that the chat is private
 */
#[Attribute]
final class FilterPublicChat implements Filter
{
    public function apply(Context $ctx): bool
    {
        return $ctx->getChatType() !== 'private';
    }
}
