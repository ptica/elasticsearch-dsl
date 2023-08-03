<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL;

/**
 * A trait which handles elasticsearch aggregation script.
 */
trait ScriptAwareTrait
{
    private mixed $script = null;

    public function getScript(): mixed
    {
        return $this->script;
    }

    public function setScript($script): static
    {
        $this->script = $script;

        return $this;
    }
}
