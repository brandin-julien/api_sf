<?php

namespace AppBundle\Controller;

use AppBundle\Entity\todoList;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // replace this example code with whatever you need

        $em = $this->getDoctrine()->getManager();

        $username = $_GET["username"];

        $user = $em->getRepository('AppBundle:User')->findByUsername($username);

        if($user == null){
            $user = new User();
            $user->setUsername($username);
            $em->persist($user);
            $em->flush();
        }

        $todoList = $em->getRepository('AppBundle:todoList')->findByUser($user);

        $allTodo = [];

        foreach ($todoList as $todo){
            $array = [
                "title" => $todo->getTitle(),
                "content" => $todo->getContent(),
                "id" => $todo->getId(),
            ];

            array_push($allTodo, $array);
        }

        $myArray = [
            "response" => "ok",
            "information" => $allTodo
        ];

        //return $this->render('index.html.twig', array('todoList' => $myArray));

        $response = new Response(json_encode($myArray));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/createTask", name="createTask")
     */
    public function createTaskAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $todoList = new todoList();

        $username = $_GET["username"];
        $title = $_GET["title"];

        $user = $em->getRepository('AppBundle:User')->findOneByUsername($username);

        $todoList->setTitle($title);
        $todoList->setUser($user);
        $todoList->setContent("");

        $em->persist($todoList);
        $em->flush();


        $myArray = [
            "response" => "ok",
        ];

        $response = new Response(json_encode($myArray));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/deleteTask", name="deleteTask")
     */
    public function deleteTaskAction(Request $request)
    {





    }




}