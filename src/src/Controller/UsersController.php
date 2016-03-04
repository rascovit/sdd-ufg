<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

	public function isAuthorized($user)
	{
		//Must be logged as teacher
		if (in_array($this->request->action, ['myAccount'])) {
			if(isset($this->loggedUser->teacher) && $this->loggedUser->teacher != null) {
                return True;
            }
		}

		return parent::isAuthorized($user);
	}

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['requestAccount', 'logout']);

        $redirectToHome = ['requestAccount', 'login'];
        $authUser = isset($this->request->Session()->read('Auth')['User']) ? true : false;
        if($authUser && in_array($this->request->params['action'], $redirectToHome)) {
            return $this->redirect('/');
        }
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
		$this->paginate = [
            'limit' => 25,
            'order' => [
                'Users.id' => 'ASC'
            ]
        ];

        $this->request->data = $this->request->query;
        $findByFilters = $this->Users->findByFilters($this->request->query);
        $users = $findByFilters['data'];

        $this->set('isFiltered', $findByFilters['isFiltered']);

        $this->set('users', $this->paginate($users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Usuário adicionado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível adicionar o usuário, tente novamente.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Request account method
     *
     * @return void Redirects on successful request account, renders view otherwise.
     */
    public function requestAccount()
    {
        $this->viewBuilder()->layout('logged-out');

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data(), [
                'associated' => []
            ]);

            if($this->Users->save($user)) {
                $teacher = $this->Users->Teachers->newEntity($this->request->data['teacher'], [
                    'associated' => ['KnowledgesTeachers']]);
                $teacher->user_id = $user->id;

                if ($this->Users->Teachers->save($teacher)) {
                    $knowledges = TableRegistry::get('Knowledges')->find('all');
                    foreach($knowledges as $knowledge) {
                        $knowledgeTeacher = $this->Users->Teachers->KnowledgesTeachers->newEntity();
                        $knowledgeTeacher->teacher_id = $teacher->id;
                        $knowledgeTeacher->knowledge_id = $knowledge->id;
                        $knowledgeTeacher->level = 3;

                        $this->Users->Teachers->KnowledgesTeachers->save($knowledgeTeacher);
                    }
                    $this->Flash->success(__('Conta solicitada com sucesso.'));
                    return $this->redirect(['action' => 'login']);
                } else {
                    $this->Users->delete($user);
                    $this->Flash->error(__('Não foi possível solicitar sua conta, cheque os campos abaixos ou tente novamente mais tarde.'));
                }
            } else {
                $this->Flash->error(__('Não foi possível solicitar sua conta, cheque os campos abaixos ou tente novamente mais tarde.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Login method
     *
     * @return void Redirects on successful request account, renders view otherwise.
     */
    public function login()
    {
        $this->viewBuilder()->layout('logged-out');

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Usuário e/ou senha inválidos, tente novamente.'));
        }
    }

    /**
     * Logout method
     *
     * @return void Redirects on successful request account, renders view otherwise.
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Usuário modificado com sucesso.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Não foi possível modificar o usuário, tente novamente.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Usuário removido com sucesso.'));
        } else {
            $this->Flash->error(__('Não foi possível remover o usuário, tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

	/**
     * My Account method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to Teachers/edit/<teacher_id>.
     */
    public function myAccount()
    {
        return $this->redirect(['controller' => 'Teachers', 'action' => 'edit', $this->loggedUser->teacher->id]);
    }
}
