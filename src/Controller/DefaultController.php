<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home");
     */
    public function show(EntityManagerInterface $entityManager) {
        $jobs = $entityManager->getRepository(Job::class)->findBy(
            array(),
            array('fetch_date' => 'DESC', 'last_update' => 'DESC'),
            25
        );

        if(!$jobs) {
            throw $this->createNotFoundException('No jobs found');
        }
        //dd($jobs[0]->getFetchDate());
        //dd($jobs[0]->getFetchDate()->getDate());
        return $this->render('homepage.html.twig', [
            'jobs' => $jobs
        ]);
    }
}