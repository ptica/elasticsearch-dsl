<?php

namespace ONGR\ElasticsearchDSL\InnerHit;

class ParentInnerHit extends NestedInnerHit
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'parent';
    }
}
