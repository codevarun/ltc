<?php

namespace Ltc\BlogBundle\Document;

use Ltc\DocBundle\Document\Doc;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(
 *   collection="blog_entry",
 *   repositoryClass="Ltc\BlogBundle\Document\BlogEntryRepository"
 * )
 * @MongoDB\UniqueIndex(keys={"slug"="asc"})
 */
class BlogEntry extends Doc
{
    /**
     * Overrides Doc.slug to give it sluggable options
     *
     * @var string
     * @MongoDB\Field(type="string")
     * @Gedmo\Slug(unique="true", updatable="true", fields={"title"})
     */
    protected $slug;

    /**
     * Overrides Doc.title to give it sluggable options
     *
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * Tells whether the doc has a manually set publication date in string format
     *
     * @return bool
     */
    public function hasPublicationDate()
    {
        return false;
    }

    /**
     * Gets a unique comment identifier, usable as a slug
     *
     * @return string
     **/
    public function getCommentThreadId()
    {
        return 'table-ronde-'.$this->getSlug();
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return 'Table Ronde - '.$this->getTitle();
    }
}
