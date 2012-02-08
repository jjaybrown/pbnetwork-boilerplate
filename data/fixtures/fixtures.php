<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Michael
 * Date: 15.03.11
 * Time: 18:32
 * To change this template use File | Settings | File Templates.
 */
$newQuote = new \App\Entity\Quote();
$newQuote->setWording("Don’t let the past steal your present.");
$newQuote->setAuthor("Cherralea Morgen");
$newQuote->setSource("Brain");
$em->persist($newQuote);
$em->flush();

$newEvent = new \App\Entity\Event();
$newEvent->setName("My Birthday");
$newEvent->setStart(new \DateTime("2012-08-05"));
$newEvent->setEnd(new \DateTime("2012-08-05"));
$em->persist($newEvent);
$em->flush();