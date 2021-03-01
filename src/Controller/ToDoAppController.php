<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Todo;

class ToDoAppController extends AbstractController
{
    /**
     * @Route("/", name="todoApp")
     */
    public function index(): Response
    {
        $todos = $this->getDoctrine()->getRepository(Todo::class)->findBy([], ['id'=>'DESC']);

        return $this->render('index.html.twig', ['todos'=>$todos]);
    }

     /**
     * @Route("/create", name="createTask", methods={"POST"})
     */
    public function createTask(Request $request): Response
    {
        $input = trim($request->request->get('inputTodo'));
        if(empty($input))
            return $this->redirectToRoute('todoApp');
        $entityMan = $this->getDoctrine()->getManager();
        $todo = new Todo;
        $todo->setTitle($input);
        $entityMan->persist($todo);
        $entityMan->flush();

        return $this->redirectToRoute('todoApp');

    }

     /**
     * @Route("/movetodo/{id}", name="moveTodo")
     */
    public function moveTodo($id): Response
    {
        $entityMan = $this->getDoctrine()->getManager();
        $todo = $entityMan->getRepository(Todo::class)->find($id);

        $todo->setStatus(!($todo->getStatus()));
        $entityMan->flush();
        return $this->redirectToRoute('todoApp');
    }

    
     /**
     * @Route("/deletetodo/{id}", name="deleteTodo")
     */
    public function deleteTodo($id): Response
    {
        $entityMan = $this->getDoctrine()->getManager();
        $todo = $entityMan->getRepository(Todo::class)->find($id);

        $entityMan->remove($todo);
        $entityMan->flush();
        return $this->redirectToRoute('todoApp');
    }
}
