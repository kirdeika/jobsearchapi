<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use voku\helper\HtmlDomParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JobController extends AbstractController
{
    /**
     * @Route("/fetchNew", name="app_fetch_new");
     */
    public function fetchNew(EntityManagerInterface $entityManager) {
        $allLinks = array();

        $html = HtmlDomParser::file_get_html('https://tesonet.com/career/all-positions/');

        foreach ($html->find('a.js-job-link') as $e) {
            if(strpos($e->href, 'php') !== false ) {
                $jobId = $e->getAttribute('data-job-id');

                $requirements = [];
                $descParagaphs = [];
                foreach($html->find('div[data-job-id]') as $jobDiv) {
                    if(strpos($jobDiv->getAttribute('data-job-id'), $jobId) !== false) {
                        //$jobDiv->find('div.career-lever__lists ul')[1]->innertext;

                        foreach($jobDiv->find('div.career-lever__lists ul')[1] as $expectations) {
                            $requirements[] = filter_var($expectations, FILTER_SANITIZE_STRIPPED);
                        }

                        foreach($jobDiv->find('div.career-lever__job-description div') as $descP) {
                            if($descP->innertext !== '<br>') {
                                $descParagaphs[] = filter_var($descP->innertext, FILTER_SANITIZE_STRIPPED);
                            }
                        }
                    }
                }

                if(strpos($e->findOne('p.position-title')->innertext, 'PHP') !== false ) {
                    $allLinks[] = [
                        'title' => $e->findOne('p.position-title')->innertext,
                        'link' => 'https://tesonet.com/career/all-positions/' . $e->href,
                        'requirements'=> $requirements,
                        'description-paragraphs' => $descParagaphs,
                        'city'=> $e->find('div.d-flex p')[2]->innertext
                    ];
                }
            }
        }
        //dd($allLinks);
        foreach($allLinks as $oneJob) {
            if(!$entityManager->getRepository(Job::class)->findBy([
                'title'=>$oneJob['title'],
                'link'=>$oneJob['link']
            ])) {
                $job = new Job();
                $job->setTitle($oneJob['title']);
                $job->setLink($oneJob['link']);
                $job->setRequirements(implode("\n", $oneJob['requirements']));
                $job->setDescriptionParagraphs(implode("\n", $oneJob['description-paragraphs']));
                $job->setCity($oneJob['city']);
                $job->setFetchDate(new \DateTime('now'));
                $job->setLastUpdate(new \DateTime('now'));
                $entityManager->persist($job);
                $entityManager->flush();
            } else {
                $existingJob = $entityManager->getRepository(Job::class)->findOneBy([
                    'title'=>$oneJob['title'],
                    'link'=>$oneJob['link']
                ]);
                $existingJob->setLastUpdate(new \DateTime('now'));
                $entityManager->persist($existingJob);
                $entityManager->flush();
            }

        }
        //TODO - CHECK IF THERE IS ALREADY SUCH ENTRIES
        //$records = $entityManager->getRepository(Job::class)->findBy(['title'=>'PHP Developer (Access Product)']);
        //dd($records);
        return new JsonResponse($allLinks, Response::HTTP_OK);
    }

    /**
     * @Route("/fetchNew2")
     */
    public function fetchNew2(EntityManagerInterface $entityManager) {
        $html = HtmlDomParser::file_get_html('https://www.cvbankas.lt/?miestas=&padalinys%5B%5D=&keyw=PHP');
        foreach ($html->find('article.list_article') as $e) {
            if(!strpos($e->class, 'jobadlist_article_vip')) {   //Throwing away VIP ads

                if(!$entityManager->getRepository(Job::class)->findBy([
                    'title'=>$e->findOne('h3')->innertext,
                    'link'=>$e->findOne('a')->href
                ])) {
                    $job = new Job;
                    $job->setTitle($e->findOne('h3')->innertext);
                    $job->setLink($e->findOne('a')->href);
                    $job->setCity($e->findOne('span.list_city')->innertext);
                    $job->setFetchDate(new \DateTime('now'));
                    $job->setLastUpdate(new \DateTime('now'));
                    $job->setWorkplaceName($e->findOne('span.dib')->innertext);
                    $job->setSalary($e->findOne('span.salary_amount')->innertext . ' ' . $e->findOne('span.salary_calculation')->innertext);
                    $entityManager->persist($job);
                    $entityManager->flush();
                    //dd($job);
                } else {
                    $existingJob = $entityManager->getRepository(Job::class)->findOneBy([
                        'title'=>$e->findOne('h3')->innertext,
                        'link'=>$e->findOne('a')->href
                    ]);
                    $existingJob->setLastUpdate(new \DateTime('now'));
                    $existingJob->setWorkplaceName($e->findOne('span.dib')->innertext);
                    $existingJob->setSalary($e->findOne('span.salary_amount')->innertext . ' ' . $e->findOne('span.salary_calculation')->innertext);
                    $entityManager->persist($existingJob);
                    $entityManager->flush();
                }
            }
        }
        return new JsonResponse($entityManager->getRepository(Job::class)->findAll(), Response::HTTP_OK);
    }
    /**
     * @Route("/fetchNew3")
     */
    public function fetchNew3(EntityManagerInterface $entityManager) {
        $html = HtmlDomParser::file_get_html('https://www.cvmarket.lt/joboffers.php?_track=index_click_job_search&op=search&search_location=landingpage&ga_track=homepage&search%5Bkeyword%5D=PHP&mobile_search%5Bkeyword%5D=&tmp_city=&tmp_cat=&tmp_city=&tmp_category=&search%5Bkeyword%5D=PHP&search%5Bexpires_days%5D=&search%5Bjob_lang%5D=&search%5Bsalary%5D=&search%5Bjob_salary%5D=3');
        foreach ($html->find('tr.f_job_row2') as $e) {
            if(!$entityManager->getRepository(Job::class)->findBy([
                'title'=>$e->findOne('a.f_job_title')->innertext,
                'link'=>$e->findOne('a.f_job_title')->href
            ])) {
                $job = new Job;
                $job->setTitle($e->findOne('a.f_job_title')->innertext);
                $job->setLink('https://www.cvmarket.lt/' . $e->findOne('a.f_job_title')->href);
                $job->setCity($e->findOne('div.f_job_city')->innertext);
                $job->setFetchDate(new \DateTime('now'));
                $job->setLastUpdate(new \DateTime('now'));
                $job->setWorkplaceName($e->findOne('span.f_job_company')->innertext);
                $job->setSalary(strip_tags($e->findOne('span.f_job_salary')->innertext . ' ' . $e->findOne('span.salary-type')->innertext));
                $entityManager->persist($job);
                $entityManager->flush();
                //dd($job);
            } else {
                $existingJob = $entityManager->getRepository(Job::class)->findOneBy([
                    'title'=>$e->findOne('a.f_job_title')->innertext,
                    'link'=>$e->findOne('a.f_job_title')->href
                ]);
                $existingJob->setLastUpdate(new \DateTime('now'));
                $existingJob->setWorkplaceName($e->findOne('span.f_job_company')->innertext);
                $existingJob->setSalary(strip_tags($e->findOne('span.f_job_salary')->innertext . ' ' . $e->findOne('span.salary-type')->innertext));
                $entityManager->persist($existingJob);
                $entityManager->flush();
            }
        }
        return new JsonResponse($entityManager->getRepository(Job::class)->findAll(), Response::HTTP_OK);
    }
}