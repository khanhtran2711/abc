<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project_show", methods={"GET"})
     */
    public function showAction(ProjectRepository $repo): Response
    {
        // $projects = $repo->findAllGreaterThanPrice();
        $projects = $repo->findAll();
        $data = [];
        foreach($projects as $p){
            
            $data[] = [
                'name' => $p->getName(),
                'content' => $p->getContent()
            ];
        }
        return $this->json($data);
    }

    /**
     * @Route("/project", name="project_new", methods={"POST"})
     */
    public function addAction(ManagerRegistry $reg, Request $re): Response
    {
        $entity = $reg->getManager();
        //1 receive a request from Client
        $re = $this->transformJsonBody($re);
        //2 from this request, create a new Project 
        $p = new Project();
        $p->setName($re->get('name'));
        $p->setContent($re->get('content'));
        //3. persist, flush
        $entity->persist($p);
        $entity->flush();
        //4. return json
        return $this->json([
            'name' => $p->getName(),
            'content' => $p->getContent()
        ]);
        // return $this->json('Created a new project successfully with id '.$p->getId());
    }

    public function transformJsonBody(Request $re){
        $data = json_decode($re->getContent(), true);
        if($data === null){
            return $re;
        }
        $re->request->replace($data);
        return $re;
    }

     /**
     * @Route("/project/{id}", name="project_edit", methods={"PUT"})
     */
    public function editAction(ManagerRegistry $reg, Request $re, int $id,
    ProjectRepository $repo): Response
    {
        //1. find by Id
        $p = $repo->find($id);
        if(!$p){
            return $this->json("No project found");
        }
        //2. receive request
        $re = $this->transformJsonBody($re);
        //3. set new value for this project 
        $p->setName($re->get('name'));
        $p->setContent($re->get('content'));
        //4. persist , flush
        $entity = $reg->getManager();
        $entity->persist($p);
        $entity->flush();
        //5. return json
        return $this->json([
            'name' => $p->getName(),
            'content' => $p->getContent()
        ]);
    }

    /**
     * @Route("/project/{id}", name="project_del", methods={"DELETE"})
     */
    public function delAction(ManagerRegistry $reg, Request $re, int $id,
    ProjectRepository $repo): Response
    {
        $entity = $reg->getManager();
        $p = $repo->find($id);
        if(!$p){
            return $this->json("No project found");
            
        }
        $entity->remove($p);
        $entity->flush();

        return $this->json("Deleted a project successfully with id ". $id);
    }
}
