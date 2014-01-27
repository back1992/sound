<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="blogpost") */
class BlogPost
{
    private $title;
    private $body;
    private $createdAt;

    // ...
}