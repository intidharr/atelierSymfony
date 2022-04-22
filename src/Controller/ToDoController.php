<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('todos')) {
            $todos = [
                'achat' => ' acheter un clé usb',
                'revision' => ' reviser mes cours',
                'devoirs' => ' faire mes exercice'
            ];
            $session->set('todos', $todos);
            $this->addFlash('List', "Hello!la liste des Todos est initialisée");

        }
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}', name: 'addtodo')]
    public function addTodo($name, $content, SessionInterface $session)
    {
       if(!$session->has('todos')) {
           $this->addFlash('error', 'La liste des todos n est pas encore initialisé');

       }
       else{
           $todos=$session->get('todos');
           if (isset($todos[$name])){
               $this->addFlash('error', "le ToDo $name existe déja!");
           }
           else{
               $todos[$name]=$content;
               $session->set('todos',$todos);
               $this->addFlash('succès', "le nouveau ToDo $name est ajouté avec succès ");


           }

       }
        return $this->redirectToRoute('todo');

    }

    #[Route('/todo/update/{name}/{content}', name: 'updatetodo')]
    public function updateTodo($name, $content, SessionInterface $session){
        if(!$session->has('todos')) {
            $this->addFlash('error', 'La liste des todos n est pas encore initialisé');

        }
        else{
            $todos=$session->get('todos');
            if (!isset($todos[$name])){
                $this->addFlash('error', "le ToDo $name n'existe pas!");
            }
            else{
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('succès', "le ToDo $name est modifié avec succès ");

            }

        }
        return $this->redirectToRoute('todo');

    }

    #[Route('/todo/delete/{name}', name: 'deletetodo')]
    public function deleteTodo($name, SessionInterface $session)
    {
        if(!$session->has('todos')) {
            $this->addFlash('error', 'La liste des todos n est pas encore initialisé');

        }
        else{
            $todos=$session->get('todos');
            if (!isset($todos[$name])){
                $this->addFlash('error', "le ToDo $name n'existe pas!");
            }
            else{
                unset($todos[$name]);
                $session->set('todos',$todos);
                $this->addFlash('succès', "le nouveau ToDo $name est supprimé avec succès ");


            }

        }
        return $this->redirectToRoute('todo');

    }


    #[Route('/todo/reset', name: 'resettodo')]
    public function resetTodo( SessionInterface $session)
    {
        $session->remove('todos');
        return $this->redirectToRoute('todo');

    }

}