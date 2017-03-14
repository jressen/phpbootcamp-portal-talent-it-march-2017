<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class PostRepository extends EntityRepository {

    /**
     * @return Query
     */
    public function queryLatest() {
        return $this->getEntityManager()
                        ->createQuery('
                SELECT p
                FROM AppBundle:Post p
                WHERE p.publishedAt <= :now
                ORDER BY p.publishedAt DESC
            ')
                        ->setParameter('now', new \DateTime())
        ;
    }

    /**
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findLatest($page = 1) {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryLatest(), false));
        $paginator->setMaxPerPage(Post::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @param string $tag
     * 
     * @return Query
     */
    public function queryTag($tag) {
        
        return $this->getEntityManager()
                        ->createQuery('  
                                                           
                    SELECT p FROM AppBundle:Post p JOIN p.tags t WHERE t.name =' .  ":tagger" .
                    '
           ')
                        ->setParameter('tagger', $tag)
        ;
    }

    /**
     * @param string $tag
     *
     * @return Pagerfanta
     */
    public function findTag($tag) {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryTag($tag), false));
        $paginator->setMaxPerPage(Post::NUM_ITEMS);
        $paginator->setCurrentPage(1);
        return $paginator;
    }

}
