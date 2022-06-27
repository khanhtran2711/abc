<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\Type\AnimalType;
use App\Repository\AnimalRepository;
use App\Repository\CatAniRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

date_default_timezone_set("Asia/Ho_Chi_Minh");
class AnimalController extends AbstractController
{
    /**
     * @Route("/addAni", name="addAni")
     */
    public function addAniAction(ManagerRegistry $res,
    ValidatorInterface $valid): Response
    {
        $entity = $res->getManager();
        $animal = new Animal();
        $animal->setName("meo");
        $animal->setBirthday(new \DateTime());
        $animal->setEmail("khanh@gmail.com");
        $animal->setWeight(12);

        $error = $valid->validate($animal);
        if(count($error)>0){
            $err_string = (string)$error;
            return new Response($err_string,400);
        }

        $entity->persist($animal);
        $entity->flush();

        return $this->json($animal);
    }

    /**
     * @Route("/show/animal", name="showAniAction")
     */
    public function showAniAction(AnimalRepository $repo): Response
    {
        //1 
        // $animal = $repo->findOneBy([
        //     'email'=> "khanh@gmail.com"
        // ]);
        // $animal = $repo->findOneBy([
        //     'email'=> "khanh@gmail.com",
        //     'weight' => 13
        // ]);

        //2
        // $animal = $repo->findBy([
        //     'email'=> "khanh1@gmail.com"
        // ]);
        // if(!$animal){
        //     throw $this->createNotFoundException("No animal found");
        // }
        //3 findAll''
        // $animal = $repo->findAll();
        $animal = $repo->findAllGreaterThan(12);

        return $this->json($animal);
    }

    /**
     * @Route("/add_new", name="addNew")
     */
    public function addNewAction(ManagerRegistry $reg,
    CatAniRepository $repo): Response
    {
        $entity = $reg->getManager();

        //1. Create a new Animal
        $animal = new Animal();
        $animal->setName("Bob");
        $animal->setBirthday(new \DateTime());
        $animal->setEmail("abc@gmail.com");
        $animal->setWeight(1);

        //2. Find CatAni by Id=1
        $catAni = $repo->find(1);//object

        $animal->setCat($catAni);
        //3 Insert all info from a new Animal
        $entity->persist($animal);
        $entity->flush();
        //MODE: LAZY, EAGER
        return $this->json($animal,
        Response::HTTP_OK,[],[
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object){
                return $object->getId();
            }
        ]);
    }
    /**
     * @Route("/show_all", name="showAll")
     */
    public function showAllAction(AnimalRepository $repo): Response
    {
        $ani = $repo->findAniCat();
        
        return $this->json($ani);
    }

    /**
     * @Route("/add/animal", name="addAnimal")
     */
    public function addAnimal(Request $req, ManagerRegistry $res): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($req);
        $entity = $res->getManager();

        //handleform when user clicks submit button.
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $animal->setName($data->getName());
            $animal->setBirthday($data->getBirthday());
            $animal->setEmail($data->getEmail());
            $animal->setWeight($data->getWeight());
            $animal->setCat($data->getCat());
            $entity->persist($animal);
            $entity->flush();
            return $this->json([
                'id' => $animal->getId()
            ]);
        }
        return $this->render("animal/index.html.twig",[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/animal/{id}", name="editAnimal")
     */
    public function editAnimal(Request $req, ManagerRegistry $res,
    AnimalRepository $repo, int $id): Response
    {
        $animal = $repo->find($id);//animal by id
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($req);
        $entity = $res->getManager();

        //handleform when user clicks submit button.
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $animal->setName($data->getName());
            $animal->setBirthday($data->getBirthday());
            $animal->setEmail($data->getEmail());
            $animal->setWeight($data->getWeight());
            $animal->setCat($data->getCat());
            $entity->persist($animal);
            $entity->flush();
            return $this->json([
                'id' => $animal->getId()
            ]);
        }
        return $this->render("animal/index.html.twig",[
            'form' => $form->createView()
        ]);
    }

}
